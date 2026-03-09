<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\User;

echo "--- EMPLOYEES ---\n";
foreach (Employee::withoutGlobalScopes()->get() as $e) {
    $u = User::withoutGlobalScopes()->find($e->user_id);
    echo "Employee ID: {$e->id}, UserID: {$e->user_id}, CompanyID: {$e->company_id}, Name: " . ($u ? $u->name : 'MISSING') . "\n";
}

echo "\n--- USERS ---\n";
foreach (User::withoutGlobalScopes()->get() as $u) {
    echo "User ID: {$u->id}, Name: {$u->name}, CompanyID: " . ($u->company_id ?? 'NULL') . "\n";
}
