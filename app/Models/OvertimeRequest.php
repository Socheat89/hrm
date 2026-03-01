<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'ot_date',
        'start_time',
        'end_time',
        'total_hours',
        'reason',
        'status',
        'admin_comment',
    ];

    /**
     * Get the employee that requested the overtime.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
