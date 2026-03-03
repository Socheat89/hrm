<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::query()->latest()->paginate(15);

        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('admin.leave-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'default_days' => ['required', 'numeric', 'min:0'],
            'is_paid' => ['nullable', 'boolean'],
        ]);

        LeaveType::query()->create($validated);

        return redirect()->route('admin.leave-types.index')->with('status', 'Leave type created.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name,'.$leaveType->id],
            'default_days' => ['required', 'numeric', 'min:0'],
            'is_paid' => ['nullable', 'boolean'],
        ]);

        $leaveType->update($validated);

        return redirect()->route('admin.leave-types.index')->with('status', 'Leave type updated.');
    }

    public function destroy(LeaveType $leaveType)
    {
        try {
            $leaveType->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['error' => 'Cannot delete leave type because it is being used in leave requests.']);
            }
            throw $e;
        }

        return redirect()->route('admin.leave-types.index')->with('status', 'Leave type deleted.');
    }
}
