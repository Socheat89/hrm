<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanySettingRequest;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = CompanySetting::query()->firstOrCreate(['id' => 1], [
            'company_name' => 'HRM Company',
            'primary_color' => '#1f4f82',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'current_plan_name' => 'Standard',
            'payroll_enabled' => true,
            'overtime_rate_per_hour' => 0,
            'late_deduction_per_minute' => 0,
            'allowed_late_count' => 0,
            'late_deduction_amount' => 0,
        ]);

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(UpdateCompanySettingRequest $request)
    {
        $setting = CompanySetting::query()->firstOrCreate(['id' => 1], [
            'company_name' => 'HRM Company',
            'primary_color' => '#1f4f82',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'current_plan_name' => 'Standard',
            'payroll_enabled' => true,
            'overtime_rate_per_hour' => 0,
            'late_deduction_per_minute' => 0,
            'allowed_late_count' => 0,
            'late_deduction_amount' => 0,
        ]);

        $setting->update([
            ...$request->validated(),
            'payroll_enabled' => $request->boolean('payroll_enabled'),
        ]);

        Cache::forget('ui_company_setting');

        return back()->with('status', 'Company settings updated.');
    }
}
