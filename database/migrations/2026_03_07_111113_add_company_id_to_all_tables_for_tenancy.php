<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The tables to add the company_id column to.
     */
    protected array $tables = [
        'users',
        'branches',
        'departments',
        'employees',
        'attendance_sessions',
        'schedules',
        'attendance_logs',
        'leave_types',
        'leave_requests',
        'activity_logs',
        'payrolls',
        'payroll_items',
        'company_settings',
        'attendance_qr_tokens',
        'attendance_rejection_logs',
        'overtime_requests',
        'change_dayoff_requests',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'company_id')) {
                        $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'company_id')) {
                        $table->dropForeign([$tableName . '_company_id_foreign']);
                        $table->dropColumn('company_id');
                    }
                });
            }
        }
    }
};
