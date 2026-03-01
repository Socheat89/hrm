<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChangeDayoffRequest;

class ChangeDayoffRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'original_date' => 'required|date',
            'requested_date' => 'required|date|different:original_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $employee = auth()->user()->employee;

        ChangeDayoffRequest::create([
            'employee_id' => $employee->id,
            'original_date' => $request->original_date,
            'requested_date' => $request->requested_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Change Day Off request submitted successfully.');
    }
}
