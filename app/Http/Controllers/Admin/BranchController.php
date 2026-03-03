<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::query()->latest()->paginate(15);

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(StoreBranchRequest $request)
    {
        Branch::query()->create($request->validated());

        return redirect()->route('admin.branches.index')->with('status', 'Branch created.');
    }

    public function show(Branch $branch)
    {
        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()->route('admin.branches.index')->with('status', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->employees()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete branch because it has employees assigned.']);
        }

        try {
            $branch->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['error' => 'Cannot delete branch because it is linked to other records (Attendance, Payroll, etc).']);
            }
            throw $e;
        }

        return redirect()->route('admin.branches.index')->with('status', 'Branch deleted.');
    }
}
