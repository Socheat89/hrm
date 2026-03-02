<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('subscription_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->date('expiry_date')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['status', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
