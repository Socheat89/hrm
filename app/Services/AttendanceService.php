<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\AttendanceQrToken;
use App\Models\AttendanceRejectionLog;
use App\Models\AttendanceSession;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    private const DEFAULT_FLOW = ['morning_in', 'lunch_out', 'lunch_in', 'evening_out'];
    private const TWO_SCAN_FLOW = ['morning_in', 'evening_out'];

    /** Scan type display labels */
    public const LABELS = [
        'morning_in'  => 'Morning In',
        'lunch_out'   => 'Lunch Out',
        'lunch_in'    => 'Lunch In',
        'evening_out' => 'Evening Out',
    ];

    /** Required predecessors for each scan type */
    private const PREREQUISITES = [
        'morning_in'  => [],
        'lunch_out'   => ['morning_in'],
        'lunch_in'    => ['morning_in', 'lunch_out'],
        'evening_out' => ['morning_in'],
    ];

    public function __construct(
        private readonly GeoFenceService $geoFenceService,
        private readonly TelegramAttendanceNotifier $telegramAttendanceNotifier
    )
    {
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Process an attendance scan request for an employee.
     *
     * @throws ValidationException
     */
    public function scan(Employee $employee, array $payload): AttendanceLog
    {
        return DB::transaction(function () use ($employee, $payload): AttendanceLog {
            $today    = Carbon::today();
            $now      = Carbon::now();
            $branch   = $employee->branch;
            $scanType = $payload['scan_type'];

            // ── 1. Pre-flight employee/leave checks ──────────────────────────
            $this->guardEmployeeStatus($employee);
            $this->guardLeaveStatus($employee, $today);

            if (! $branch) {
                throw ValidationException::withMessages(['branch' => 'Employee is not assigned to a branch.']);
            }

            if (! $branch->is_active) {
                throw ValidationException::withMessages(['branch' => 'Branch is currently inactive.']);
            }

            // 2. Get or open today session
            $session       = $this->resolveSession($employee, $branch->id, $today);
            $existingTypes = $session->logs->pluck('scan_type')->toArray();

            // 3. Duplicate scan check
            if (in_array($scanType, $existingTypes)) {
                throw ValidationException::withMessages([
                    'scan_type' => sprintf(
                        '%s has already been recorded today.',
                        self::LABELS[$scanType] ?? $scanType
                    ),
                ]);
            }

            // 4. Schedule and scan-flow validation
            $schedule = Schedule::query()
                ->where('branch_id', $branch->id)
                ->where('day_of_week', (int) $today->dayOfWeek)
                ->first();

            $scanFlow = $this->resolveFlowFromSchedule($schedule);

            if (! in_array($scanType, $scanFlow, true)) {
                throw ValidationException::withMessages([
                    'scan_type' => 'This scan type is disabled for the current attendance setting.',
                ]);
            }

            // 5. Scan order validation
            $this->validateScanOrder($scanType, $existingTypes, $scanFlow);

            if ($schedule) {
                $this->validateTimeWindow($scanType, $schedule, $today, $now);
            }

            // 6. GPS & QR validation – store rejection on failure
            $distanceMeters = null;
            try {
                if ($branch->requiresGps()) {
                    $distanceMeters = $this->validateGps($payload, $branch);
                }
                if ($branch->requiresQr()) {
                    $this->validateQr($payload, $branch, $today, $now);
                }
            } catch (ValidationException $e) {
                $this->storeRejection(
                    $employee, $branch->id, $scanType, $payload,
                    collect($e->errors())->flatten()->first() ?? 'Scan rejected.',
                    $distanceMeters
                );
                throw $e;
            }

            // 7. Create valid attendance log
            $log = AttendanceLog::query()->create([
                'attendance_session_id' => $session->id,
                'employee_id'           => $employee->id,
                'branch_id'             => $branch->id,
                'scan_type'             => $scanType,
                'scanned_at'            => $now,
                'latitude'              => $payload['latitude'] ?? null,
                'longitude'             => $payload['longitude'] ?? null,
                'distance_from_branch'  => $distanceMeters !== null ? round($distanceMeters, 2) : null,
                'device_info'           => $payload['device_info'] ?? request()->userAgent(),
                'ip_address'            => request()->ip(),
                'qr_token'              => $payload['qr_token'] ?? null,
            ]);

            // 8. Recalculate session totals
            $this->recalculateSession($session->fresh(['logs', 'branch']));

            DB::afterCommit(function () use ($log): void {
                try {
                    $this->telegramAttendanceNotifier->sendScan($log);
                } catch (\Throwable $exception) {
                    Log::warning('Attendance scan saved but Telegram callback failed.', [
                        'attendance_log_id' => $log->id,
                        'error' => $exception->getMessage(),
                    ]);
                }
            });

            return $log;
        });
    }

    public function recalculateSession(AttendanceSession $session): void
    {
        $logs     = $session->logs->keyBy('scan_type');
        $schedule = Schedule::query()
            ->where('branch_id', $session->branch_id)
            ->where('day_of_week', (int) Carbon::parse($session->attendance_date)->dayOfWeek)
            ->first();

        $lateMinutes       = 0;
        $earlyLeaveMinutes = 0;
        $workMinutes       = 0;
        $overtimeMinutes   = 0;

        if ($schedule && $logs->has('morning_in') && $schedule->morning_in) {
            $startIn = Carbon::parse($session->attendance_date . ' ' . $schedule->morning_in);

            $endIn = null;
            if ($schedule->lunch_out) {
                $endIn = Carbon::parse($session->attendance_date . ' ' . $schedule->lunch_out);
            } elseif ($schedule->evening_out) {
                $endIn = Carbon::parse($session->attendance_date . ' ' . $schedule->evening_out);
            }

            $actualIn = Carbon::parse($logs->get('morning_in')->scanned_at);

            if ($actualIn->gt($startIn) && (! $endIn || $actualIn->lte($endIn))) {
                $lateMinutes = max(0, (int) $startIn->diffInMinutes($actualIn));
            } else {
                $lateMinutes = 0;
            }
        }

        if ($logs->has('morning_in') && $logs->has('evening_out')) {
            $in          = Carbon::parse($logs->get('morning_in')->scanned_at);
            $out         = Carbon::parse($logs->get('evening_out')->scanned_at);
            $workMinutes = max(0, (int) $in->diffInMinutes($out));

            if ($logs->has('lunch_out') && $logs->has('lunch_in')) {
                $lunchOut    = Carbon::parse($logs->get('lunch_out')->scanned_at);
                $lunchIn     = Carbon::parse($logs->get('lunch_in')->scanned_at);
                $workMinutes -= max(0, (int) $lunchOut->diffInMinutes($lunchIn));
            }
        }

        if ($schedule && $logs->has('evening_out') && $schedule->evening_out) {
            $expectedOut       = Carbon::parse($session->attendance_date . ' ' . $schedule->evening_out)
                ->subMinutes((int) $schedule->early_leave_grace_minutes);
            $actualOut         = Carbon::parse($logs->get('evening_out')->scanned_at);
            $earlyLeaveMinutes = max(0, (int) $actualOut->diffInMinutes($expectedOut, false) * -1);

            if ($schedule->morning_in) {
                $scheduledMinutes = Carbon::parse($session->attendance_date . ' ' . $schedule->morning_in)
                    ->diffInMinutes(Carbon::parse($session->attendance_date . ' ' . $schedule->evening_out));
                $overtimeMinutes = max(0, $workMinutes - $scheduledMinutes);
            }
        }

        $session->update([
            'late_minutes'        => $lateMinutes,
            'early_leave_minutes' => $earlyLeaveMinutes,
            'work_minutes'        => $workMinutes,
            'overtime_minutes'    => $overtimeMinutes,
        ]);
    }

    /**
     * Determine the next expected scan type and window status for an employee today.
     * Returns [scan_type, label, status] where status is 'open' or 'completed'.
     */
    public function resolveNextScan(Employee $employee): array
    {
        $today = Carbon::today();
        $session = AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $schedule = Schedule::query()
            ->where('branch_id', $employee->branch_id)
            ->where('day_of_week', (int) $today->dayOfWeek)
            ->first();

        $scanFlow = $this->resolveFlowFromSchedule($schedule);
        $scanned = $session?->logs?->pluck('scan_type')->toArray() ?? [];

        foreach ($scanFlow as $type) {
            if (! in_array($type, $scanned, true)) {
                return [$type, (self::LABELS[$type] ?? $type) . ' Window Open', 'open'];
            }
        }

        return ['evening_out', 'All scans completed for today', 'completed'];
    }

    public function resolveDailyScanFlow(Employee $employee, ?Carbon $date = null): array
    {
        $targetDate = $date ?? Carbon::today();

        $schedule = Schedule::query()
            ->where('branch_id', $employee->branch_id)
            ->where('day_of_week', (int) $targetDate->dayOfWeek)
            ->first();

        return $this->resolveFlowFromSchedule($schedule);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Guards
    // ─────────────────────────────────────────────────────────────────────────

    private function guardEmployeeStatus(Employee $employee): void
    {
        if ($employee->employment_status === 'suspended') {
            throw ValidationException::withMessages(['employee' => 'Your account is suspended. Please contact HR.']);
        }
        if ($employee->employment_status === 'resigned') {
            throw ValidationException::withMessages(['employee' => 'Your employment is no longer active.']);
        }
    }

    private function guardLeaveStatus(Employee $employee, Carbon $today): void
    {
        $onLeave = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        if ($onLeave) {
            throw ValidationException::withMessages(['leave' => 'You are on approved leave today. Scanning is not allowed.']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Session management
    // ─────────────────────────────────────────────────────────────────────────

    private function resolveSession(Employee $employee, int $branchId, Carbon $today): AttendanceSession
    {
        $session = AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today->toDateString())
            ->first();

        if ($session) {
            return $session;
        }

        try {
            return AttendanceSession::query()->create([
                'employee_id'     => $employee->id,
                'attendance_date' => $today->toDateString(),
                'branch_id'       => $branchId,
            ])->load('logs');
        } catch (\Illuminate\Database\UniqueConstraintViolationException) {
            return AttendanceSession::query()
                ->with('logs')
                ->where('employee_id', $employee->id)
                ->whereDate('attendance_date', $today->toDateString())
                ->firstOrFail();
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Validation helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function validateScanOrder(string $scanType, array $existingTypes, array $scanFlow): void
    {
        foreach (self::PREREQUISITES[$scanType] ?? [] as $required) {
            if (! in_array($required, $scanFlow, true)) {
                continue;
            }

            if (! in_array($required, $existingTypes)) {
                $reqLabel  = self::LABELS[$required]  ?? $required;
                $scanLabel = self::LABELS[$scanType]   ?? $scanType;
                throw ValidationException::withMessages([
                    'scan_type' => "Cannot record {$scanLabel}. Please complete {$reqLabel} first.",
                ]);
            }
        }
    }

    private function validateTimeWindow(string $scanType, Schedule $schedule, Carbon $today, Carbon $now): void
    {
        if (! $schedule->{$scanType}) {
            return;
        }

        // Window is now informational only. We do not reject outside the range.
        // Late minutes are calculated in recalculateSession() using schedule bounds.
    }

    private function buildWindow(string $scanType, Schedule $schedule, Carbon $today): array
    {
        $d = $today->toDateString();

        $starts = [
            'morning_in'  => $schedule->morning_in
                ? Carbon::parse("{$d} {$schedule->morning_in}")->subHour()
                : null,
            'lunch_out'   => $schedule->lunch_out
                ? Carbon::parse("{$d} {$schedule->lunch_out}")->subMinutes(30)
                : null,
            'lunch_in'    => $schedule->lunch_in
                ? Carbon::parse("{$d} {$schedule->lunch_in}")->subMinutes(30)
                : null,
            'evening_out' => $schedule->evening_out
                ? Carbon::parse("{$d} {$schedule->evening_out}")->subHour()
                : null,
        ];

        $ends = [
            'morning_in'  => $schedule->lunch_out
                ? Carbon::parse("{$d} {$schedule->lunch_out}")
                : Carbon::parse($d)->endOfDay(),
            'lunch_out'   => $schedule->lunch_in
                ? Carbon::parse("{$d} {$schedule->lunch_in}")
                : Carbon::parse($d)->endOfDay(),
            'lunch_in'    => $schedule->evening_out
                ? Carbon::parse("{$d} {$schedule->evening_out}")
                : Carbon::parse($d)->endOfDay(),
            'evening_out' => Carbon::parse($d)->endOfDay(),
        ];

        return [$starts[$scanType] ?? null, $ends[$scanType] ?? null];
    }

    private function resolveFlowFromSchedule(?Schedule $schedule): array
    {
        if (! $schedule) {
            return self::DEFAULT_FLOW;
        }

        if (! $schedule->lunch_out && ! $schedule->lunch_in) {
            return self::TWO_SCAN_FLOW;
        }

        return self::DEFAULT_FLOW;
    }

    private function validateGps(array $payload, $branch): float
    {
        if (empty($payload['latitude']) || empty($payload['longitude'])) {
            throw ValidationException::withMessages(['location' => 'GPS location is required. Please allow location access.']);
        }

        $distance = $this->geoFenceService->distanceMeters(
            (float) $payload['latitude'],
            (float) $payload['longitude'],
            (float) $branch->latitude,
            (float) $branch->longitude,
        );

        if ($distance > (int) $branch->allowed_radius_meters) {
            throw ValidationException::withMessages([
                'location' => sprintf(
                    'Outside branch GPS range. You are %.0f m away (limit: %d m).',
                    $distance,
                    $branch->allowed_radius_meters
                ),
            ]);
        }

        return $distance;
    }

    private function validateQr(array $payload, $branch, Carbon $today, Carbon $now): void
    {
        $token = trim($payload['qr_token'] ?? '');

        if ($token === '') {
            throw ValidationException::withMessages(['qr_token' => 'QR code is required. Please scan the QR code provided by your admin.']);
        }

        $valid = AttendanceQrToken::query()
            ->where('branch_id', $branch->id)
            ->where('token', $token)
            ->whereDate('token_date', $today->toDateString())
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', $now))
            ->exists();

        if (! $valid) {
            throw ValidationException::withMessages(['qr_token' => 'QR code is invalid, expired, or already revoked.']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Rejection logging
    // ─────────────────────────────────────────────────────────────────────────

    private function storeRejection(
        Employee $employee,
        ?int $branchId,
        string $scanType,
        array $payload,
        string $reason,
        ?float $distance = null
    ): void {
        try {
            AttendanceRejectionLog::query()->create([
                'employee_id'          => $employee->id,
                'branch_id'            => $branchId,
                'scan_type'            => $scanType,
                'latitude'             => $payload['latitude'] ?? null,
                'longitude'            => $payload['longitude'] ?? null,
                'distance_from_branch' => $distance !== null ? round($distance, 2) : null,
                'rejection_reason'     => $reason,
                'device_info'          => $payload['device_info'] ?? null,
                'ip_address'           => request()->ip(),
                'user_agent'           => request()->userAgent(),
                'qr_token'             => $payload['qr_token'] ?? null,
            ]);
        } catch (\Throwable) {
            // Never let rejection logging break the main scan flow
        }
    }
}
