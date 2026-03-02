<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'allowed_radius_meters',
        'scan_mode',
        'is_active',
    ];

    // Possible scan modes
    public const SCAN_MODE_GPS     = 'gps';
    public const SCAN_MODE_QR      = 'qr';
    public const SCAN_MODE_GPS_QR  = 'gps_qr';

    protected function casts(): array
    {
        return [
            'latitude'  => 'float',
            'longitude' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function requiresGps(): bool
    {
        return in_array($this->scan_mode ?? 'gps', [self::SCAN_MODE_GPS, self::SCAN_MODE_GPS_QR]);
    }

    public function requiresQr(): bool
    {
        return in_array($this->scan_mode ?? 'gps', [self::SCAN_MODE_QR, self::SCAN_MODE_GPS_QR]);
    }

    public function scanModeLabel(): string
    {
        return match ($this->scan_mode ?? 'gps') {
            'gps'     => 'GPS Only',
            'qr'      => 'QR Only',
            'gps_qr'  => 'GPS + QR',
            default   => 'GPS Only',
        };
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
