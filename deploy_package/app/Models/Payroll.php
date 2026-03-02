<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'period_start',
        'period_end',
        'base_salary',
        'overtime_amount',
        'late_deduction',
        'leave_deduction',
        'bonus',
        'other_deduction',
        'net_salary',
        'status',
        'paid_at',
        'generated_by',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'generated_at' => 'datetime',
            'paid_at' => 'datetime',
            'base_salary' => 'decimal:2',
            'overtime_amount' => 'decimal:2',
            'late_deduction' => 'decimal:2',
            'leave_deduction' => 'decimal:2',
            'bonus' => 'decimal:2',
            'other_deduction' => 'decimal:2',
            'net_salary' => 'decimal:2',
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

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }
}
