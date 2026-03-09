<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = SubscriptionPlan::paginate(10);
        return view('superadmin.subscription_plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.subscription_plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscription_plans,name',
            'price' => 'required|numeric|min:0',
            'employee_limit' => 'nullable|integer|min:1',
            'branch_limit' => 'nullable|integer|min:1',
            'feature_list' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['feature_list'] = $request->input('feature_list', []);

        SubscriptionPlan::create($validated);

        return redirect()->route('superadmin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('superadmin.subscription_plans.edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscription_plans,name,' . $subscriptionPlan->id,
            'price' => 'required|numeric|min:0',
            'employee_limit' => 'nullable|integer|min:1',
            'branch_limit' => 'nullable|integer|min:1',
            'feature_list' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['feature_list'] = $request->input('feature_list', []);

        $subscriptionPlan->update($validated);

        return redirect()->route('superadmin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->companies()->exists()) {
            return redirect()->route('superadmin.subscription-plans.index')
                ->with('error', 'Cannot delete plan because it is currently assigned to one or more companies.');
        }

        $subscriptionPlan->delete();

        return redirect()->route('superadmin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
}
