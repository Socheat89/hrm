<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

DB::transaction(function() {
    $employees = Employee::withoutGlobalScopes()->get();
    foreach ($employees as $employee) {
        if ($employee->user_id && $employee->company_id) {
            $user = User::withoutGlobalScopes()->find($employee->user_id);
            if ($user && $user->company_id !== $employee->company_id) {
                echo "Fixing User ID: {$user->id} ({$user->name}). Setting CompanyID to {$employee->company_id}\n";
                $user->company_id = $employee->company_id;
                $user->save();
            }
        }
    }
});

echo "Done fixing users.\n";
