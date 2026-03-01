<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeDayoffRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'original_date',
        'requested_date',
        'reason',
        'status',
        'admin_comment',
    ];

    /**
     * Get the employee that requested the day-off change.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
