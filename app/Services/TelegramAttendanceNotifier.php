<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\CompanySetting;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAttendanceNotifier
{
    private const CHECK_IN_TYPES = ['morning_in', 'lunch_in'];
    private const FULL_FLOW = ['morning_in', 'lunch_out', 'lunch_in', 'evening_out'];
    private const TWO_FLOW = ['morning_in', 'evening_out'];

    public function sendScan(AttendanceLog $log): void
    {
        try {
            $setting = CompanySetting::query()->find(1)
                ?? CompanySetting::query()->latest('id')->first();

            if (! $setting?->telegram_scan_enabled) {
                return;
            }

            $botToken = trim((string) ($setting->telegram_bot_token ?: config('services.telegram.bot_token')));
            $chatId = trim((string) ($setting->telegram_chat_id ?: config('services.telegram.chat_id')));

            if ($botToken === '' || $chatId === '') {
                return;
            }

            $log->loadMissing(['employee.user', 'employee.department', 'branch', 'attendanceSession']);

            $employeeName = $log->employee?->user?->name ?? ('Employee #' . $log->employee_id);
            $employeeCode = $log->employee?->employee_id ?? ('EMP-' . $log->employee_id);
            $departmentName = $log->employee?->department?->name ?? 'N/A';
            $position = $log->employee?->position ?? 'N/A';
            $branchName = $log->branch?->name ?? ('Branch #' . $log->branch_id);
            $scanLabel = in_array($log->scan_type, self::CHECK_IN_TYPES, true) ? 'Check-In' : 'Check-Out';
            $isLate = $this->isLateForScanType($log);
            $status = $scanLabel . ' ' . ($isLate ? '🔴 Late' : '🔵 Good');

            $distanceText = $log->distance_from_branch !== null
                ? round((float) $log->distance_from_branch) . ' m'
                : 'N/A';

            $locationText = 'N/A';
            if ($log->latitude !== null && $log->longitude !== null) {
                $locationText = sprintf(
                    'https://maps.google.com/?q=%s,%s',
                    number_format((float) $log->latitude, 6, '.', ''),
                    number_format((float) $log->longitude, 6, '.', '')
                );
            }

            $message = implode("\n", [
                'Name : ' . $employeeName,
                'Status : ' . $status,
                '-------------------------------------',
                'ID : ' . $employeeCode,
                'Department : ' . $departmentName,
                'Position : ' . $position,
                'Date/Time : ' . ($log->scanned_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s')),
                'Area : ' . $branchName,
                'Distance : ' . $distanceText,
                'Location : ' . $locationText,
            ]);

            try {
                Http::asForm()
                    ->timeout(8)
                    ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ])
                    ->throw();
            } catch (\Throwable) {
                Http::asForm()
                    ->withoutVerifying()
                    ->timeout(8)
                    ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ])
                    ->throw();
            }
        } catch (\Throwable $exception) {
            Log::warning('Telegram attendance notification failed.', [
                'attendance_log_id' => $log->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function isLateForScanType(AttendanceLog $log): bool
    {
        if (! $log->scanned_at || ! $log->branch_id) {
            return false;
        }

        $scanDate = Carbon::parse($log->scanned_at);
        $dateOnly = $scanDate->toDateString();

        $schedule = Schedule::query()
            ->where('branch_id', $log->branch_id)
            ->where('day_of_week', (int) $scanDate->dayOfWeek)
            ->first();

        if (! $schedule) {
            return false;
        }

        $scheduleTimes = [
            'morning_in'  => $schedule->morning_in,
            'lunch_out'   => $schedule->lunch_out,
            'lunch_in'    => $schedule->lunch_in,
            'evening_out' => $schedule->evening_out,
        ];

        if (empty($scheduleTimes[$log->scan_type])) {
            return false;
        }

        $flow = (! $schedule->lunch_out && ! $schedule->lunch_in) ? self::TWO_FLOW : self::FULL_FLOW;
        $start = Carbon::parse($dateOnly . ' ' . $scheduleTimes[$log->scan_type]);

        $end = Carbon::parse($dateOnly)->endOfDay();
        $currentIndex = array_search($log->scan_type, $flow, true);
        if ($currentIndex !== false) {
            for ($next = $currentIndex + 1; $next < count($flow); $next++) {
                $nextType = $flow[$next];
                if (! empty($scheduleTimes[$nextType])) {
                    $end = Carbon::parse($dateOnly . ' ' . $scheduleTimes[$nextType]);
                    break;
                }
            }
        }

        return $scanDate->gt($start) && $scanDate->lte($end);
    }
}
