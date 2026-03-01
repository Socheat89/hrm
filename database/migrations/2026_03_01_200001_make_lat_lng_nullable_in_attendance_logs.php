<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support ALTER COLUMN — use table rebuild approach
        // For MySQL/PostgreSQL, standard change() works directly.
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // Rebuild the table with nullable lat/lon
            DB::statement('PRAGMA foreign_keys = OFF');

            DB::statement('CREATE TABLE attendance_logs_rebuild (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                attendance_session_id INTEGER NOT NULL REFERENCES attendance_sessions(id) ON DELETE CASCADE,
                employee_id INTEGER NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
                scan_type TEXT NOT NULL CHECK(scan_type IN (\'morning_in\',\'lunch_out\',\'lunch_in\',\'evening_out\')),
                scanned_at DATETIME NOT NULL,
                latitude REAL NULL,
                longitude REAL NULL,
                device_info TEXT NULL,
                ip_address TEXT NULL,
                qr_token TEXT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                branch_id INTEGER NULL REFERENCES branches(id) ON DELETE SET NULL,
                distance_from_branch REAL NULL
            )');

            DB::statement('INSERT INTO attendance_logs_rebuild
                SELECT id, attendance_session_id, employee_id, scan_type, scanned_at,
                       latitude, longitude, device_info, ip_address, qr_token,
                       created_at, updated_at, branch_id, distance_from_branch
                FROM attendance_logs');

            DB::statement('DROP TABLE attendance_logs');
            DB::statement('ALTER TABLE attendance_logs_rebuild RENAME TO attendance_logs');

            // Recreate indexes
            DB::statement('CREATE UNIQUE INDEX attendance_logs_session_scan_unique ON attendance_logs(attendance_session_id, scan_type)');
            DB::statement('CREATE INDEX attendance_logs_employee_scanned_index ON attendance_logs(employee_id, scanned_at)');

            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            Schema::table('attendance_logs', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable()->change();
                $table->decimal('longitude', 10, 7)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable(false)->change();
            $table->decimal('longitude', 10, 7)->nullable(false)->change();
        });
    }
};
