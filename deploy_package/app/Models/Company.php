<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_plan_id',
        'status',
        'expiry_date',
        'monthly_price',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'monthly_price' => 'decimal:2',
        ];
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
