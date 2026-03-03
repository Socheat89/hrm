<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::query()->with('branch')->latest()->paginate(15);

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('admin.departments.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Department::query()->create($validated);

        return redirect()->route('admin.departments.index')->with('status', 'Department created.');
    }

    public function edit(Department $department)
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('admin.departments.edit', compact('department', 'branches'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('status', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['error' => 'Cannot delete department because it is linked to other records.']);
            }
            throw $e;
        }

        return redirect()->route('admin.departments.index')->with('status', 'Department deleted.');
    }
}
