<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePayrollRequest;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function __construct(private readonly PayrollService $payrollService)
    {
    }

    public function index()
    {
        $payrolls = Payroll::query()->with('employee.user')->latest()->paginate(20);

        return view('admin.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::query()->with('user')->orderBy('employee_id')->get();
        $branches = Branch::query()->orderBy('name')->get();

        return view('admin.payrolls.create', compact('employees', 'branches'));
    }

    public function generate(GeneratePayrollRequest $request)
    {
        $validated = $request->validated();

        $employeeQuery = Employee::query();
        if (! empty($validated['branch_id'])) {
            $employeeQuery->where('branch_id', $validated['branch_id']);
        }
        if (! empty($validated['employee_id'])) {
            $employeeQuery->where('id', $validated['employee_id']);
        }

        $employees = $employeeQuery->get();

        foreach ($employees as $employee) {
            $this->payrollService->generate(
                $employee,
                Carbon::parse($validated['period_start']),
                Carbon::parse($validated['period_end']),
                $request->user()->id,
            );
        }

        return redirect()->route('admin.payrolls.index')->with('status', 'Payroll generated successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.user', 'items');

        return view('admin.payrolls.show', compact('payroll'));
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('status', 'Payroll marked as paid.');
    }

    public function download(Payroll $payroll)
    {
        $payroll->load('employee.user', 'items');
        $pdf = Pdf::loadView('admin.payrolls.pdf', compact('payroll'));

        return $pdf->download('payslip-'.$payroll->employee->employee_id.'-'.$payroll->period_start->format('Ym').'.pdf');
    }
}
