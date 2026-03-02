<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChangeDayoffRequest;
use Illuminate\Http\Request;

class ChangeDayoffRequestController extends Controller
{
    public function index(Request $request)
    {
        $changeDayoffRequests = ChangeDayoffRequest::query()
            ->with(['employee.user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.change-dayoff-requests.index', [
            'changeDayoffRequests' => $changeDayoffRequests,
            'dayoffRequests' => $changeDayoffRequests,
        ]);
    }

    public function updateStatus(Request $request, ChangeDayoffRequest $changeDayoffRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string|max:1000'
        ]);

        $changeDayoffRequest->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        return back()->with('success', 'Change Day Off request updated successfully.');
    }
}
