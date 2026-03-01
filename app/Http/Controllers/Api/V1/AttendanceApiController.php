<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceScanRequest;
use App\Models\AttendanceSession;
use App\Services\AttendanceService;

class AttendanceApiController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index()
    {
        $employee = auth()->user()->employee;

        return response()->json(
            AttendanceSession::query()
                ->with('logs')
                ->where('employee_id', $employee->id)
                ->latest('attendance_date')
                ->paginate(20)
        );
    }

    public function scan(AttendanceScanRequest $request)
    {
        $employee = $request->user()->employee;
        $log = $this->attendanceService->scan($employee, $request->validated());

        return response()->json(['message' => 'Recorded', 'data' => $log], 201);
    }
}
