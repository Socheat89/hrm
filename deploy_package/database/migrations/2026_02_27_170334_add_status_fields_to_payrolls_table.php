<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid'])->default('pending')->after('net_salary');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->index(['status', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex(['status', 'period_start']);
            $table->dropColumn(['status', 'paid_at']);
        });
    }
};
