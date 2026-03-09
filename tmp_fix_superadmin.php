<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$email = 'superadmin@example.com';
$password = 'password';

$user = DB::table('users')->where('email', $email)->first();

if ($user) {
    DB::table('users')->where('email', $email)->update([
        'password' => Hash::make($password),
        'is_super_admin' => 1,
        'is_active' => 1
    ]);
    echo "USER_UPDATED_TO_PASSWORD\n";
} else {
    DB::table('users')->insert([
        'name' => 'Super Administrator',
        'email' => $email,
        'password' => Hash::make($password),
        'is_super_admin' => 1,
        'is_active' => 1,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "USER_CREATED_AND_READY\n";
}
