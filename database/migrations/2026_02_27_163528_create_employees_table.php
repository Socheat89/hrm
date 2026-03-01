<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('position');
            $table->enum('salary_type', ['monthly', 'daily']);
            $table->decimal('base_salary', 12, 2);
            $table->enum('employment_status', ['active', 'suspended', 'resigned'])->default('active');
            $table->date('join_date');
            $table->decimal('leave_balance_days', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['branch_id', 'employment_status']);
            $table->index(['department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
