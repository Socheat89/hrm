<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();
        $cacheKey = 'admin_dashboard_summary_'.$today->format('Ymd');

        $summary = Cache::remember($cacheKey, 300, function () use ($today) {
            $lateEmployees = AttendanceSession::query()
                ->with('employee.user')
                ->whereDate('attendance_date', $today->toDateString())
                ->where('late_minutes', '>', 0)
                ->orderByDesc('late_minutes')
                ->limit(8)
                ->get();

            $pendingLeaves = LeaveRequest::query()
                ->with(['employee.user', 'leaveType'])
                ->where('status', 'pending')
                ->latest()
                ->limit(8)
                ->get();

            $chartRows = AttendanceSession::query()
                ->selectRaw('DATE(attendance_date) as date, COUNT(*) as total')
                ->whereMonth('attendance_date', $today->month)
                ->whereYear('attendance_date', $today->year)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'totalEmployees' => Employee::query()->count(),
                'todayAttendance' => AttendanceSession::query()->whereDate('attendance_date', $today->toDateString())->count(),
                'lateEmployeesCount' => AttendanceSession::query()->whereDate('attendance_date', $today->toDateString())->where('late_minutes', '>', 0)->count(),
                'onLeaveToday' => LeaveRequest::query()
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $today->toDateString())
                    ->whereDate('end_date', '>=', $today->toDateString())
                    ->count(),
                'monthlyPayrollCost' => Payroll::query()
                    ->whereMonth('period_start', $today->month)
                    ->whereYear('period_start', $today->year)
                    ->sum('net_salary'),
                'chartLabels' => $chartRows->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('d M')),
                'chartValues' => $chartRows->pluck('total'),
                'lateEmployees' => $lateEmployees,
                'pendingLeaves' => $pendingLeaves,
            ];
        });

        return view('admin.dashboard', $summary);
    }
}
