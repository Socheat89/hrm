<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAttendanceNotifier
{
    public function sendScan(AttendanceLog $log): void
    {
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

        $log->loadMissing(['employee.user', 'branch']);

        $employeeName = $log->employee?->user?->name ?? ('Employee #' . $log->employee_id);
        $branchName = $log->branch?->name ?? ('Branch #' . $log->branch_id);
        $scanLabel = AttendanceService::LABELS[$log->scan_type] ?? $log->scan_type;

        $message = implode("\n", [
            '📌 Attendance Scan',
            '👤 Employee: ' . $employeeName,
            '🏢 Branch: ' . $branchName,
            '🕒 Type: ' . $scanLabel,
            '⏱ Time: ' . $log->scanned_at?->format('Y-m-d H:i:s'),
        ]);

        try {
            Http::asForm()
                ->timeout(8)
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ])
                ->throw();
        } catch (\Throwable $exception) {
            try {
                Http::asForm()
                    ->withoutVerifying()
                    ->timeout(8)
                    ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ])
                    ->throw();
            } catch (\Throwable $fallbackException) {
                Log::warning('Telegram attendance notification failed.', [
                    'attendance_log_id' => $log->id,
                    'error' => $exception->getMessage(),
                    'fallback_error' => $fallbackException->getMessage(),
                ]);
            }
        }
    }
}
