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
        Schema::table('schedules', function (Blueprint $table) {
            // Add new composite unique constraint that includes employee_id FIRST
            // This index starts with branch_id so it can maintain the FK constraint
            $table->unique(['branch_id', 'day_of_week', 'employee_id'], 'unique_schedule_entry');
            
            // Drop old unique constraint
            $table->dropUnique('schedules_branch_id_day_of_week_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Restore old unique constraint FIRST (to satisfy FK)
            $table->unique(['branch_id', 'day_of_week'], 'schedules_branch_id_day_of_week_unique');
            
            // Drop new unique constraint
            $table->dropUnique('unique_schedule_entry');
        });
    }
};
