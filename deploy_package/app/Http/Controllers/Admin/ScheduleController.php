<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
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

    public function index(Request $request)
    {
        $branches  = Branch::query()->where('is_active', true)->orderBy('name')->get();
        $branchId  = $request->integer('branch_id') ?: $branches->first()?->id;
        $schedules = Schedule::query()
            ->with('branch')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        return view('admin.schedules.index', [
            'branches'  => $branches,
            'schedules' => $schedules,
            'dayNames'  => self::DAY_NAMES,
            'branchId'  => $branchId,
        ]);
    }

    public function create(Request $request)
    {
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.schedules.create', [
            'branches' => $branches,
            'dayNames' => self::DAY_NAMES,
            'selected' => $request->only('branch_id', 'day_of_week'),
        ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        $data = $request->validated();

        // Upsert on branch_id + day_of_week (update if exists)
        Schedule::query()->updateOrCreate(
            ['branch_id' => $data['branch_id'], 'day_of_week' => $data['day_of_week']],
            $data
        );

        return redirect()->route('admin.schedules.index', ['branch_id' => $data['branch_id']])
            ->with('status', 'Schedule saved for ' . self::DAY_NAMES[$data['day_of_week']] . '.');
    }

    public function edit(Schedule $schedule)
    {
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.schedules.edit', [
            'schedule' => $schedule,
            'branches' => $branches,
            'dayNames' => self::DAY_NAMES,
        ]);
    }

    public function update(StoreScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return redirect()->route('admin.schedules.index', ['branch_id' => $schedule->branch_id])
            ->with('status', 'Schedule updated.');
    }

    public function destroy(Schedule $schedule)
    {
        $branchId = $schedule->branch_id;
        $schedule->delete();

        return redirect()->route('admin.schedules.index', ['branch_id' => $branchId])
            ->with('status', 'Schedule removed.');
    }
}
