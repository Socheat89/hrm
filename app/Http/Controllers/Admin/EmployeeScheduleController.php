<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Schedule;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    private const DAY_NAMES = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function index(Request $request, Employee $employee)
    {
        $employee->load('user', 'branch');

        // Fetch schedules specifically for this employee
        $schedules = Schedule::query()
            ->where('employee_id', $employee->id)
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        // Fetch default branch schedules for comparison/fallback display (optional)
        $branchSchedules = Schedule::query()
            ->where('branch_id', $employee->branch_id)
            ->whereNull('employee_id')
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        return view('admin.employees.schedules.index', [
            'employee'        => $employee,
            'schedules'       => $schedules,
            'branchSchedules' => $branchSchedules,
            'dayNames'        => self::DAY_NAMES,
        ]);
    }

    public function store(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'day_of_week'               => ['required', 'integer'],
            'morning_in'                => ['nullable', 'date_format:H:i'],
            'lunch_out'                 => ['nullable', 'date_format:H:i', 'after:morning_in'],
            'lunch_in'                  => ['nullable', 'date_format:H:i', 'after:lunch_out'],
            'evening_out'               => ['nullable', 'date_format:H:i', 'after:lunch_in'],
            'late_grace_minutes'        => ['nullable', 'integer', 'min:0'],
            'early_leave_grace_minutes' => ['nullable', 'integer', 'min:0'],
            'scan_times'                => ['nullable', 'integer', 'in:2,4'],
        ]);

        $scanTimes = (int) ($data['scan_times'] ?? 4);
        if ($scanTimes === 2) {
            $data['lunch_out'] = null;
            $data['lunch_in'] = null;
        }
        unset($data['scan_times']);

        $isAllDays = (int) $data['day_of_week'] === -1;

        $saveData = array_merge($data, [
            'branch_id'   => $employee->branch_id, // Keep branch_id for reference, though logic uses employee_id
            'employee_id' => $employee->id,
        ]);

        if ($isAllDays) {
            foreach (array_keys(self::DAY_NAMES) as $dayNumber) {
                Schedule::updateOrCreate(
                    ['employee_id' => $employee->id, 'day_of_week' => $dayNumber],
                    array_merge($saveData, ['day_of_week' => $dayNumber])
                );
            }
            return back()->with('status', 'Schedule updated for all days.');
        }

        Schedule::updateOrCreate(
            ['employee_id' => $employee->id, 'day_of_week' => $data['day_of_week']],
            $saveData
        );

        return back()->with('status', 'Schedule updated.');
    }

    public function destroy(Employee $employee, Schedule $schedule)
    {
        if ($schedule->employee_id !== $employee->id) {
            abort(403);
        }

        $schedule->delete();

        return back()->with('status', 'Custom schedule removed. Employee will act according to branch schedule.');
    }
}
