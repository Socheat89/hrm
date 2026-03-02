<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\AttendanceRejectionLog;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date    = $request->input('date', now()->toDateString());
        $tab     = $request->input('tab', 'all'); // all | late | rejected

        $attendanceLogs = AttendanceLog::query()
            ->with(['employee.user', 'employee.branch', 'employee.department', 'attendanceSession'])
            ->when($request->filled('branch_id'), fn ($q) => $q->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->integer('employee_id')))
            ->when($tab === 'late', fn ($q) => $q->whereHas('attendanceSession', fn ($sq) => $sq->where('late_minutes', '>', 0)))
            ->whereDate('scanned_at', $date)
            ->latest('scanned_at')
            ->paginate(30)
            ->withQueryString();

        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $scheduleMap = Schedule::query()
            ->where('day_of_week', $dayOfWeek)
            ->get()
            ->keyBy('branch_id');

        // Rejected / flagged scans for the date
        $rejectedLogs = AttendanceRejectionLog::query()
            ->with(['employee.user', 'branch'])
            ->whereDate('created_at', $date)
            ->when($request->filled('branch_id'), fn ($q) => $q->where('branch_id', $request->integer('branch_id')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Summary counts for today
        $summary = [
            'total'    => AttendanceLog::query()->whereDate('scanned_at', $date)->count(),
            'late'     => AttendanceSession::query()->whereDate('attendance_date', $date)->where('late_minutes', '>', 0)->count(),
            'rejected' => AttendanceRejectionLog::query()->whereDate('created_at', $date)->count(),
            'overtime' => AttendanceSession::query()->whereDate('attendance_date', $date)->where('overtime_minutes', '>', 0)->count(),
        ];

        $branches  = Branch::query()->orderBy('name')->get();
        $employees = Employee::query()->with('user')->orderBy('employee_id')->get();

        return view('admin.attendance.index', [
            'attendanceLogs'     => $attendanceLogs,
            'rejectedLogs'       => $rejectedLogs,
            'branches'           => $branches,
            'employees'          => $employees,
            'selectedDate'       => $date,
            'activeTab'          => $tab,
            'summary'            => $summary,
            'scheduleMap'        => $scheduleMap,
        ]);
    }
}
