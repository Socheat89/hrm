<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\CompanySetting;
use App\Models\LeaveRequest;
use App\Models\Payroll;

class PanelController extends Controller
{
    public function __invoke()
    {
        $employee = auth()->user()->employee;
        $today = now()->toDateString();

        $todaySession = AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $onLeaveToday = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        $todayStatus = 'Absent';
        if ($onLeaveToday) {
            $todayStatus = 'On Leave';
        } elseif ($todaySession) {
            $todayStatus = $todaySession->late_minutes > 0 ? 'Late' : 'Present';
        }

        $monthlySessions = AttendanceSession::query()
            ->where('employee_id', $employee->id)
            ->whereYear('attendance_date', now()->year)
            ->whereMonth('attendance_date', now()->month)
            ->get();

        $monthlyLeaves = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->whereMonth('start_date', now()->month)
            ->count();

        $timelineLogs = collect(['morning_in', 'lunch_out', 'lunch_in', 'evening_out'])
            ->map(function ($scanType) use ($todaySession) {
                $log = $todaySession?->logs?->firstWhere('scan_type', $scanType);

                return [
                    'label' => str($scanType)->replace('_', ' ')->title(),
                    'time' => $log?->scanned_at?->format('H:i') ?? 'Not Scanned',
                    'scanned' => (bool) $log,
                ];
            });

        $planName = CompanySetting::query()->first()?->current_plan_name ?? 'Standard';

        $presentDays = $monthlySessions->count();
        $lateCount   = $monthlySessions->where('late_minutes', '>', 0)->count();

        return view('employee.dashboard', [
            'employee'      => $employee,
            'todayStatus'   => $todayStatus,
            'planName'      => $planName,
            'presentDays'   => $presentDays,
            'lateDays'      => $lateCount,
            'absentDays'    => 0,
            'leaveTaken'    => $monthlyLeaves,
            'overtimeHours' => round($monthlySessions->sum('overtime_minutes') / 60, 1),
            'timelineLogs'  => $timelineLogs,
            'attendanceLogs' => collect(),
            'payroll'       => Payroll::query()->where('employee_id', $employee->id)->latest()->first(),
        ]);
    }
}
