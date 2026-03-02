<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceScanRequest;
use App\Models\AttendanceSession;
use App\Models\LeaveRequest;
use App\Services\AttendanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index(Request $request)
    {
        $employee = auth()->user()->employee;
        $monthInput = (string) $request->input('month', now()->format('Y-m'));
        $month = preg_match('/^\d{4}-\d{2}$/', $monthInput) ? $monthInput : now()->format('Y-m');

        [$year, $monthNumber] = array_pad(explode('-', $month), 2, now()->month);
        $year = (int) $year;
        $monthNumber = (int) $monthNumber;

        $sessions = AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $monthNumber)
            ->get();

        $approvedLeaves = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', now()->setDate($year, $monthNumber, 1)->endOfMonth()->toDateString())
            ->whereDate('end_date', '>=', now()->setDate($year, $monthNumber, 1)->startOfMonth()->toDateString())
            ->get();

        $calendarData = [];
        foreach ($sessions as $session) {
            $status = $session->late_minutes > 0 ? 'late' : 'present';
            $calendarData[$session->attendance_date->toDateString()] = [
                'status' => $status,
                'work_hours' => round($session->work_minutes / 60, 2),
                'late_minutes' => $session->late_minutes,
                'overtime_hours' => round($session->overtime_minutes / 60, 2),
                'gps_status' => $session->has_fake_gps_flag ? 'Flagged' : 'Verified',
                'scans' => [
                    'Morning In' => optional($session->logs->firstWhere('scan_type', 'morning_in'))->scanned_at?->format('H:i'),
                    'Lunch Out' => optional($session->logs->firstWhere('scan_type', 'lunch_out'))->scanned_at?->format('H:i'),
                    'Lunch In' => optional($session->logs->firstWhere('scan_type', 'lunch_in'))->scanned_at?->format('H:i'),
                    'Evening Out' => optional($session->logs->firstWhere('scan_type', 'evening_out'))->scanned_at?->format('H:i'),
                ],
            ];
        }

        foreach ($approvedLeaves as $leave) {
            $cursor = $leave->start_date->copy();
            while ($cursor->lte($leave->end_date)) {
                if ((int) $cursor->year === $year && (int) $cursor->month === $monthNumber) {
                    $calendarData[$cursor->toDateString()] = [
                        'status' => 'leave',
                        'work_hours' => 0,
                        'late_minutes' => 0,
                        'overtime_hours' => 0,
                        'gps_status' => 'N/A',
                        'scans' => [],
                    ];
                }
                $cursor->addDay();
            }
        }

        return view('employee.attendance.index', [
            'month' => sprintf('%04d-%02d', $year, $monthNumber),
            'calendarData' => $calendarData,
            'summary' => [
                'present' => collect($calendarData)->where('status', 'present')->count(),
                'late' => collect($calendarData)->where('status', 'late')->count(),
                'leave' => collect($calendarData)->where('status', 'leave')->count(),
                'overtime' => round(collect($calendarData)->sum('overtime_hours'), 2),
            ],
        ]);
    }

    public function scan()
    {
        $employee = auth()->user()->employee;
        $employee->load('branch');

        // Today's scan logs for the summary panel
        $session = \App\Models\AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', now()->toDateString())
            ->first();

        $todayLogs = $session?->logs?->keyBy('scan_type') ?? collect();
        $scanned   = $todayLogs->keys()->toArray();
        $scanFlow  = $this->attendanceService->resolveDailyScanFlow($employee, now());

        // Resolve next available Check-In and Check-Out types
        $checkInTypes  = array_values(array_intersect(['morning_in', 'lunch_in'], $scanFlow));
        $checkOutTypes = array_values(array_intersect(['lunch_out', 'evening_out'], $scanFlow));

        $nextCheckIn  = collect($checkInTypes)->first(fn($t) => !in_array($t, $scanned));
        $nextCheckOut = collect($checkOutTypes)->first(fn($t) => !in_array($t, $scanned));

        $allDone = !$nextCheckIn && !$nextCheckOut;

        // Auto-default: next unscanned type in the full sequence
        $allTypes    = $scanFlow;
        $autoDefault = collect($allTypes)->first(fn($t) => !in_array($t, $scanned));

        return view('employee.attendance.scan', [
            'nextCheckIn'    => $nextCheckIn,
            'nextCheckOut'   => $nextCheckOut,
            'autoDefault'    => $autoDefault,
            'allDone'        => $allDone,
            'branchName'     => $employee->branch?->name,
            'scanMode'       => $employee->branch?->scan_mode ?? 'qr',
            'scanFlow'       => $scanFlow,
            'todayLogs'      => $todayLogs,
            'session'        => $session,
        ]);
    }

    public function store(AttendanceScanRequest $request)
    {
        $employee = $request->user()->employee;

        try {
            $log = $this->attendanceService->scan($employee, $request->validated());

            $checkInTypes = ['morning_in', 'lunch_in'];
            $label = in_array($log->scan_type, $checkInTypes) ? 'Check-In' : 'Check-Out';
            $late  = optional($log->attendanceSession)->late_minutes > 0
                ? sprintf(' (Late %d min)', $log->attendanceSession->late_minutes)
                : '';

            return back()->with('scan_result', [
                'type'      => 'success',
                'scan_type' => $label,
                'time'      => $log->scanned_at->format('H:i:s'),
                'status'    => $log->attendanceSession?->late_minutes > 0 ? 'Late' . $late : 'On Time',
                'distance'  => $log->distance_from_branch ? round($log->distance_from_branch) . ' m' : null,
            ]);
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput()->with('scan_result', [
                'type'    => 'error',
                'message' => collect($exception->errors())->flatten()->first() ?? 'Attendance rejected.',
            ]);
        }
    }

    public function export(Request $request)
    {
        $employee = auth()->user()->employee;
        $monthInput = (string) $request->input('month', now()->format('Y-m'));
        $month = preg_match('/^\d{4}-\d{2}$/', $monthInput) ? $monthInput : now()->format('Y-m');

        [$year, $monthNumber] = array_pad(explode('-', $month), 2, now()->month);
        $sessions = AttendanceSession::query()
            ->with('logs')
            ->where('employee_id', $employee->id)
            ->whereYear('attendance_date', (int) $year)
            ->whereMonth('attendance_date', (int) $monthNumber)
            ->orderBy('attendance_date')
            ->get();

        $pdf = Pdf::loadView('employee.attendance.pdf', compact('sessions', 'month', 'employee'));

        return $pdf->download('attendance-'.$employee->employee_id.'-'.$month.'.pdf');
    }
}
