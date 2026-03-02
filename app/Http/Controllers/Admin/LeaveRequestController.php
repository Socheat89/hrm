<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLeaveRequestStatusRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::query()
            ->with(['employee.user', 'leaveType', 'approver'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.leave-requests.index', compact('leaveRequests'));
    }

    public function updateStatus(UpdateLeaveRequestStatusRequest $request, LeaveRequest $leaveRequest)
    {
        $status = $request->validated('status');

        $leaveRequest->update([
            'status' => $status,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'admin_comment' => $request->validated('admin_comment'),
        ]);

        return back()->with('status', 'Leave request updated.');
    }
}
