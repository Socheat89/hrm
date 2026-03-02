<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('primary_color', 20)->default('#1f4f82')->after('company_name');
            $table->string('current_plan_name')->default('Standard')->after('currency');
            $table->boolean('payroll_enabled')->default(true)->after('current_plan_name');
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'current_plan_name', 'payroll_enabled']);
        });
    }
};
