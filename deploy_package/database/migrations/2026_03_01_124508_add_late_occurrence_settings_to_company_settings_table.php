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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->integer('allowed_late_count')->default(0)->after('late_deduction_per_minute');
            $table->decimal('late_deduction_amount', 8, 2)->default(0)->after('allowed_late_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['allowed_late_count', 'late_deduction_amount']);
        });
    }
};
