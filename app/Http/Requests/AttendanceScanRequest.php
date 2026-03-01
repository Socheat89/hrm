<?php

namespace App\Http\Requests;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Employee') ?? false;
    }

    public function rules(): array
    {
        // Determine the branch scan mode so we can validate qr_token conditional on it
        $employee  = $this->user()?->employee;
        $scanMode  = $employee?->branch?->scan_mode ?? 'gps';
        $needsQr   = in_array($scanMode, ['qr', 'gps_qr']);
        $needsGps  = in_array($scanMode, ['gps', 'gps_qr']);

        return [
            'scan_type'   => ['required', 'in:morning_in,lunch_out,lunch_in,evening_out'],
            'latitude'    => [$needsGps ? 'required' : 'nullable', 'numeric', 'between:-90,90'],
            'longitude'   => [$needsGps ? 'required' : 'nullable', 'numeric', 'between:-180,180'],
            'device_info' => ['nullable', 'string', 'max:255'],
            'qr_token'    => [$needsQr ? 'required' : 'nullable', 'nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'qr_token.required' => 'QR code is required. Please scan the QR provided by admin.',
            'latitude.required' => 'GPS location is required. Please allow location access.',
            'longitude.required' => 'GPS location is required. Please allow location access.',
        ];
    }
}
