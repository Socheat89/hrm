<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HrCoreSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::query()->firstOrCreate(
            ['name' => 'Head Office'],
            [
                'address' => 'Main Street',
                'latitude' => 11.5564,
                'longitude' => 104.9282,
                'allowed_radius_meters' => 300,
                'is_active' => true,
            ]
        );

        CompanySetting::query()->updateOrCreate(['id' => 1], [
            'company_name' => 'Demo HRM',
            'primary_color' => '#1f4f82',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'current_plan_name' => 'Business',
            'payroll_enabled' => true,
            'overtime_rate_per_hour' => 3,
            'late_deduction_per_minute' => 0.1,
        ]);

        $department = Department::query()->firstOrCreate([
            'branch_id' => $branch->id,
            'name' => 'Human Resources',
        ]);

        foreach (['Annual', 'Sick', 'Unpaid'] as $type) {
            LeaveType::query()->firstOrCreate([
                'name' => $type,
            ], [
                'default_days' => $type === 'Annual' ? 18 : ($type === 'Sick' ? 10 : 0),
                'is_paid' => $type !== 'Unpaid',
            ]);
        }

        foreach ([1, 2, 3, 4, 5] as $dayOfWeek) {
            Schedule::query()->firstOrCreate([
                'branch_id' => $branch->id,
                'day_of_week' => $dayOfWeek,
            ], [
                'morning_in' => '08:30:00',
                'lunch_out' => '12:00:00',
                'lunch_in' => '13:00:00',
                'evening_out' => '17:30:00',
                'late_grace_minutes' => 10,
                'early_leave_grace_minutes' => 10,
            ]);
        }

        $hrUser = User::query()->firstOrCreate(
            ['email' => 'hr@hrm.local'],
            [
                'name' => 'HR Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'branch_id' => $branch->id,
            ]
        );
        $hrUser->syncRoles(['Admin / HR']);

        $employeeUser = User::query()->firstOrCreate(
            ['email' => 'employee@hrm.local'],
            [
                'name' => 'Default Employee',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'branch_id' => $branch->id,
            ]
        );
        $employeeUser->syncRoles(['Employee']);

        Employee::query()->firstOrCreate(
            ['user_id' => $employeeUser->id],
            [
                'employee_id' => 'EMP-'.$branch->id.'-'.now()->format('ym').'-0001',
                'branch_id' => $branch->id,
                'department_id' => $department->id,
                'position' => 'Staff',
                'salary_type' => 'monthly',
                'base_salary' => 500,
                'employment_status' => 'active',
                'join_date' => now()->subMonths(3)->toDateString(),
                'leave_balance_days' => 18,
            ]
        );
    }
}
