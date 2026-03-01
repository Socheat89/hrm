<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index()
    {
        $setting = CompanySetting::query()->first();
        abort_if($setting && ! $setting->payroll_enabled, 404);

        $employee = auth()->user()->employee;
        $payrolls = Payroll::query()->with('items')->where('employee_id', $employee->id)->latest()->paginate(15);

        return view('employee.salary.index', [
            'payrolls' => $payrolls,
            'baseSalary' => $employee->base_salary,
        ]);
    }

    public function download(Payroll $payroll)
    {
        abort_unless($payroll->employee->user_id === auth()->id(), 403);

        $payroll->load('employee.user', 'items');
        $pdf = Pdf::loadView('admin.payrolls.pdf', compact('payroll'));

        return $pdf->download('my-payslip-'.$payroll->period_start->format('Ym').'.pdf');
    }
}
