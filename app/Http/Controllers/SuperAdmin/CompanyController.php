<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('subscriptionPlan')->paginate(15);
        
        return view('superadmin.companies.index', compact('companies'));
    }

    public function edit(Company $company)
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('superadmin.companies.edit', compact('company', 'plans'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'expiry_date' => 'nullable|date',
        ]);

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Company configuration updated successfully.');
    }
}
