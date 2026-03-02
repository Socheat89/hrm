<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    public function index()
    {
        return response()->json(
            LeaveRequest::query()
                ->with('leaveType')
                ->where('employee_id', auth()->user()->employee->id)
                ->latest()
                ->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        $leave = LeaveRequest::query()->create([
            'employee_id' => auth()->user()->employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Submitted', 'data' => $leave], 201);
    }
}
