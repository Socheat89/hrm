<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('ot_rate_per_hour', 8, 2)->nullable()->after('base_salary')->comment('Custom OT rate per hour');
            $table->decimal('leave_deduction_per_day', 8, 2)->nullable()->after('ot_rate_per_hour')->comment('Custom leave deduction amount per day absent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['ot_rate_per_hour', 'leave_deduction_per_day']);
        });
    }
};
