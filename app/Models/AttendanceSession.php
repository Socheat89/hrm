<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'attendance_date',
        'late_minutes',
        'early_leave_minutes',
        'work_minutes',
        'overtime_minutes',
        'has_fake_gps_flag',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'has_fake_gps_flag' => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
