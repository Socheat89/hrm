<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function generate(Employee $employee, Carbon $periodStart, Carbon $periodEnd, int $generatedBy): Payroll
    {
        return DB::transaction(function () use ($employee, $periodStart, $periodEnd, $generatedBy): Payroll {
            $settings = CompanySetting::query()->first();
            $overtimeRate = $employee->ot_rate_per_hour ?: (float) ($settings?->overtime_rate_per_hour ?? 0);
            
            $allowedLateCount = (int) ($settings?->allowed_late_count ?? 0);
            $lateDeductionAmount = (float) ($settings?->late_deduction_amount ?? 0);

            $attendanceSummary = $employee->attendanceSessions()
                ->whereBetween('attendance_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                ->selectRaw('COALESCE(SUM(overtime_minutes), 0) as overtime_minutes, SUM(CASE WHEN late_minutes > 0 THEN 1 ELSE 0 END) as late_count')
                ->first();

            $overtimeAmount = ((float) $attendanceSummary->overtime_minutes / 60) * $overtimeRate;
            
            // Late deduction calculation based on occurrences
            $actualLateCount = (int) $attendanceSummary->late_count;
            $billableLateCount = max(0, $actualLateCount - $allowedLateCount);
            $lateDeduction = $billableLateCount * $lateDeductionAmount;

            $unpaidLeaveDays = $employee->leaveRequests()
                ->where('status', 'approved')
                ->whereHas('leaveType', fn ($query) => $query->where('is_paid', false))
                ->whereBetween('start_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                ->sum('days');

            if ($employee->leave_deduction_per_day > 0) {
                $leaveDeduction = $unpaidLeaveDays * (float) $employee->leave_deduction_per_day;
            } else {
                $leaveDeduction = $employee->salary_type === 'daily'
                    ? $unpaidLeaveDays * (float) $employee->base_salary
                    : ($unpaidLeaveDays / 30) * (float) $employee->base_salary;
            }

            $baseSalary = (float) $employee->base_salary;
            $bonus = 0;
            $otherDeduction = 0;
            $netSalary = $baseSalary + $overtimeAmount + $bonus - $lateDeduction - $leaveDeduction - $otherDeduction;

            $payroll = Payroll::query()->updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'period_start' => $periodStart->toDateString(),
                    'period_end' => $periodEnd->toDateString(),
                ],
                [
                    'branch_id' => $employee->branch_id,
                    'base_salary' => $baseSalary,
                    'overtime_amount' => round($overtimeAmount, 2),
                    'late_deduction' => round($lateDeduction, 2),
                    'leave_deduction' => round($leaveDeduction, 2),
                    'bonus' => $bonus,
                    'other_deduction' => $otherDeduction,
                    'net_salary' => round($netSalary, 2),
                    'status' => 'pending',
                    'paid_at' => null,
                    'generated_by' => $generatedBy,
                    'generated_at' => now(),
                ]
            );

            $payroll->items()->delete();

            $payroll->items()->createMany([
                ['type' => 'earning', 'label' => 'Base Salary', 'amount' => $baseSalary],
                ['type' => 'earning', 'label' => 'Overtime', 'amount' => round($overtimeAmount, 2)],
                ['type' => 'deduction', 'label' => 'Late Deduction', 'amount' => round($lateDeduction, 2)],
                ['type' => 'deduction', 'label' => 'Leave Deduction', 'amount' => round($leaveDeduction, 2)],
            ]);

            return $payroll;
        });
    }
}
