<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRejectionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'scan_type',
        'latitude',
        'longitude',
        'distance_from_branch',
        'rejection_reason',
        'device_info',
        'ip_address',
        'user_agent',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'latitude'             => 'float',
            'longitude'            => 'float',
            'distance_from_branch' => 'float',
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
}
