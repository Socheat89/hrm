<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free / Trial',
                'price' => 0.00,
                'employee_limit' => 5,
                'branch_limit' => 1,
                'is_active' => true,
                'feature_list' => ['Attendance', 'Leave Management'],
            ],
            [
                'name' => 'Standard',
                'price' => 29.00,
                'employee_limit' => 50,
                'branch_limit' => 3,
                'is_active' => true,
                'feature_list' => ['Attendance', 'Leave Management', 'Payroll', 'Basic Reports'],
            ],
            [
                'name' => 'Enterprise',
                'price' => 99.00,
                'employee_limit' => null, // Unlimited
                'branch_limit' => null,   // Unlimited
                'is_active' => true,
                'feature_list' => ['Everything', 'Custom Reports', 'API Access', 'Priority Support'],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
