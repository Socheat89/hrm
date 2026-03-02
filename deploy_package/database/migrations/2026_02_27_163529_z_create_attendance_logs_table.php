<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('scan_type', ['morning_in', 'lunch_out', 'lunch_in', 'evening_out']);
            $table->timestamp('scanned_at');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('qr_token')->nullable();
            $table->timestamps();

            $table->unique(['attendance_session_id', 'scan_type']);
            $table->index(['employee_id', 'scanned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
