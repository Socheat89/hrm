<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->boolean('telegram_scan_enabled')->default(false)->after('payroll_enabled');
            $table->string('telegram_bot_token')->nullable()->after('telegram_scan_enabled');
            $table->string('telegram_chat_id')->nullable()->after('telegram_bot_token');
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['telegram_scan_enabled', 'telegram_bot_token', 'telegram_chat_id']);
        });
    }
};
