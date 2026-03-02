<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\OvertimeRequest;
use App\Models\ChangeDayoffRequest;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;

        $leaveRequests = LeaveRequest::query()
            ->with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15, ['*'], 'leave_page');

        $otRequests = OvertimeRequest::query()
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15, ['*'], 'ot_page');

        $dayoffRequests = ChangeDayoffRequest::query()
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15, ['*'], 'dayoff_page');

        $leaveTypes = LeaveType::query()->orderBy('name')->get();

        return view('employee.leave.index', compact('leaveRequests', 'otRequests', 'dayoffRequests', 'leaveTypes', 'employee'));
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $employee = $request->user()->employee;
        $validated = $request->validated();
        $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        LeaveRequest::query()->create([
            'employee_id' => $employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'] ?? null,
            'attachment_path' => $request->file('attachment')?->store('leave-attachments', 'public'),
            'status' => 'pending',
        ]);

        return back()->with('status', 'Leave request submitted.');
    }
}
