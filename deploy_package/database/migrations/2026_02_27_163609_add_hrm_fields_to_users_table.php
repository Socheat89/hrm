<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('email')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('branch_id');
            $table->string('photo_path')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('photo_path');

            $table->index(['branch_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['branch_id', 'is_active']);
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn(['phone', 'photo_path', 'is_active']);
        });
    }
};
