<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('HRM Company');
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->decimal('overtime_rate_per_hour', 8, 2)->default(0);
            $table->decimal('late_deduction_per_minute', 8, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
