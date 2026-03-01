<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceQrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'token_date',
        'token',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'token_date' => 'date',
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
