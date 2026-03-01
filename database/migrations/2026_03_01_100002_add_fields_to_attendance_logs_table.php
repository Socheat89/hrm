<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            // Direct branch reference (denormalized for fast queries)
            $table->foreignId('branch_id')
                ->nullable()
                ->after('employee_id')
                ->constrained()
                ->nullOnDelete();

            // GPS distance stored at scan time
            $table->decimal('distance_from_branch', 10, 2)->nullable()->after('longitude');

            // Indexes
            $table->index('branch_id');
            $table->index('scan_type');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['scan_type']);
            $table->dropColumn(['branch_id', 'distance_from_branch']);
        });
    }
};
