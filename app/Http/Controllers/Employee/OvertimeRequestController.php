<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Carbon\Carbon;

class OvertimeRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ot_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:1000',
        ]);

        $employee = auth()->user()->employee;
        
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $totalHours = $end->diffInMinutes($start) / 60;

        OvertimeRequest::create([
            'employee_id' => $employee->id,
            'ot_date' => $request->ot_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_hours' => round($totalHours, 2),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Overtime request submitted successfully.');
    }
}
