<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    public function index(Request $request)
    {
        $overtimeRequests = OvertimeRequest::query()
            ->with(['employee.user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.overtime-requests.index', compact('overtimeRequests'));
    }

    public function updateStatus(Request $request, OvertimeRequest $overtimeRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string|max:1000'
        ]);

        $overtimeRequest->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        return back()->with('success', 'Overtime request updated successfully.');
    }
}
