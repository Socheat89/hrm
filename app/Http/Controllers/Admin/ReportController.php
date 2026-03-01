<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $attendanceByBranch = AttendanceSession::query()
            ->selectRaw('branch_id, COUNT(*) as total')
            ->with('branch:id,name')
            ->whereMonth('attendance_date', $now->month)
            ->whereYear('attendance_date', $now->year)
            ->groupBy('branch_id')
            ->get();

        $leaveByType = LeaveRequest::query()
            ->selectRaw('leave_type_id, COUNT(*) as total')
            ->with('leaveType:id,name')
            ->whereMonth('start_date', $now->month)
            ->whereYear('start_date', $now->year)
            ->groupBy('leave_type_id')
            ->get();

        $monthlyPayroll = Payroll::query()
            ->whereMonth('period_start', $now->month)
            ->whereYear('period_start', $now->year)
            ->sum('net_salary');

        return view('admin.reports.index', compact('attendanceByBranch', 'leaveByType', 'monthlyPayroll'));
    }
}
