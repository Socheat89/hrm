<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $starter = SubscriptionPlan::query()->firstOrCreate(
            ['name' => 'Starter'],
            [
                'price' => 49,
                'employee_limit' => 50,
                'branch_limit' => 2,
                'feature_list' => ['Attendance', 'Leave', 'Basic Payroll'],
                'is_active' => true,
            ]
        );

        $business = SubscriptionPlan::query()->firstOrCreate(
            ['name' => 'Business'],
            [
                'price' => 129,
                'employee_limit' => 300,
                'branch_limit' => 8,
                'feature_list' => ['Attendance', 'Leave', 'Payroll', 'Reports', 'QR Scan'],
                'is_active' => true,
            ]
        );

        $enterprise = SubscriptionPlan::query()->firstOrCreate(
            ['name' => 'Enterprise'],
            [
                'price' => 299,
                'employee_limit' => 1000,
                'branch_limit' => 30,
                'feature_list' => ['All modules', 'Priority Support', 'API Access'],
                'is_active' => true,
            ]
        );

        Company::query()->firstOrCreate(
            ['name' => 'Alpha Manufacturing'],
            [
                'subscription_plan_id' => $business->id,
                'status' => 'active',
                'expiry_date' => now()->addMonths(1)->toDateString(),
                'monthly_price' => $business->price,
            ]
        );

        Company::query()->firstOrCreate(
            ['name' => 'Beta Services'],
            [
                'subscription_plan_id' => $starter->id,
                'status' => 'active',
                'expiry_date' => now()->addDays(10)->toDateString(),
                'monthly_price' => $starter->price,
            ]
        );

        Company::query()->firstOrCreate(
            ['name' => 'Gamma Retail'],
            [
                'subscription_plan_id' => $enterprise->id,
                'status' => 'expired',
                'expiry_date' => now()->subDays(5)->toDateString(),
                'monthly_price' => $enterprise->price,
            ]
        );
    }
}
