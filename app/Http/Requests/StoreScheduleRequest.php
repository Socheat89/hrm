<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Super Admin', 'Admin / HR']) ?? false;
    }

    public function rules(): array
    {
        return [
            'branch_id'                => ['required', 'exists:branches,id'],
            'day_of_week'              => ['required', 'integer', 'between:0,6'],
            'morning_in'               => ['nullable', 'date_format:H:i'],
            'lunch_out'                => ['nullable', 'date_format:H:i'],
            'lunch_in'                 => ['nullable', 'date_format:H:i'],
            'evening_out'              => ['nullable', 'date_format:H:i'],
            'late_grace_minutes'       => ['required', 'integer', 'min:0', 'max:120'],
            'early_leave_grace_minutes' => ['required', 'integer', 'min:0', 'max:120'],
        ];
    }
}
