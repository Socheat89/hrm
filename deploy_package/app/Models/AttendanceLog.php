<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_session_id',
        'employee_id',
        'branch_id',
        'scan_type',
        'scanned_at',
        'latitude',
        'longitude',
        'distance_from_branch',
        'device_info',
        'ip_address',
        'qr_token',
    ];

    public const SCAN_LABELS = [
        'morning_in'  => 'Morning In',
        'lunch_out'   => 'Lunch Out',
        'lunch_in'    => 'Lunch In',
        'evening_out' => 'Evening Out',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at'           => 'datetime',
            'latitude'             => 'float',
            'longitude'            => 'float',
            'distance_from_branch' => 'float',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function attendanceSession(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
