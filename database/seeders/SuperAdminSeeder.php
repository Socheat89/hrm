<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if there's already a super admin to avoid duplication
        if (User::where('is_super_admin', true)->exists()) {
            return;
        }

        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'is_super_admin' => true,
            'is_active' => true,
        ]);
        
        $this->command->info('Super Admin seeded successfully: superadmin@example.com / password');
    }
}
