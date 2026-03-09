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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->string('method')->default('KHQR');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('access_token')->nullable()->unique(); // To access the register page securely
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
