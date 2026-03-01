<x-layouts.employee page-title="Dashboard" page-description="Get a quick view of today status, monthly totals, and attendance timeline.">
    @php
        $statusStyles = [
            'Present' => ['class' => 'status-present', 'note' => 'On time and fully logged in'],
            'Late' => ['class' => 'status-late', 'note' => 'Attendance submitted with delay'],
            'Absent' => ['class' => 'status-absent', 'note' => 'No attendance session detected today'],
            'On Leave' => ['class' => 'status-leave', 'note' => 'Approved leave is active today'],
        ][$todayStatus] ?? ['class' => 'status-default', 'note' => 'Daily status is being calculated'];
    @endphp

    <style>
        .dashboard-hero {
            background: linear-gradient(145deg, #123759 0%, #1f4f7e 58%, #356896 100%);
            color: #fff;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 24px 34px rgba(13, 42, 73, 0.28);
        }

        .dashboard-hero .subtle {
            color: rgba(235, 243, 252, 0.82);
            font-size: 0.8rem;
        }

        .dashboard-hero .status-pill {
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .timeline-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.55rem;
        }

        .timeline-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            border: 1px solid #d9e3f0;
            background: #f8fbff;
            border-radius: 14px;
            padding: 0.7rem 0.8rem;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            flex-shrink: 0;
            margin-top: 0.2rem;
            background: #bccbdb;
        }

        .timeline-dot.active {
            background: #1c7f4d;
            box-shadow: 0 0 0 4px rgba(31, 138, 82, 0.18);
        }

        .payroll-mini {
            border-radius: 14px;
            background: #edf4ff;
            border: 1px solid #d0e0f4;
            padding: 0.75rem 0.85rem;
        }
    </style>

    <section class="dashboard-hero p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
            <div>
                <p class="subtle mb-1">Today Status</p>
                <h2 class="h3 mb-1 fw-bold">{{ $todayStatus }}</h2>
                <p class="subtle mb-0">{{ $statusStyles['note'] }}</p>
                <div class="mt-3">
                    <span class="status-pill">
                        {{ $todayStatus }}
                    </span>
                </div>
            </div>
            <div class="text-md-end">
                <p class="subtle mb-1">Current Plan</p>
                <div class="h5 fw-bold mb-2">{{ $planName }}</div>
                <p class="subtle mb-0">Employee ID: {{ $employee->employee_id }}</p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="{{ route('employee.attendance.scan') }}" class="btn-brand">Scan Attendance</a>
            <a href="{{ route('employee.attendance.index') }}" class="btn-quiet">View Calendar</a>
        </div>
    </section>

    <section class="stat-grid mb-4">
        <article class="stat-card">
            <small class="muted">Present Days</small>
            <div class="value">{{ $presentDays }}</div>
            <small class="muted">Current month</small>
        </article>
        <article class="stat-card">
            <small class="muted">Late Count</small>
            <div class="value">{{ $lateCount }}</div>
            <small class="muted">Current month</small>
        </article>
        <article class="stat-card">
            <small class="muted">Leave Count</small>
            <div class="value">{{ $leaveCount }}</div>
            <small class="muted">Approved days</small>
        </article>
        <article class="stat-card">
            <small class="muted">Overtime Hours</small>
            <div class="value">{{ $overtimeHours }}</div>
            <small class="muted">Total OT this month</small>
        </article>
    </section>

    <section class="card-soft p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">Today Timeline</h3>
            <span class="badge-soft">{{ now()->format('M d') }}</span>
        </div>

        <ul class="timeline-list">
            @foreach($timelineLogs as $item)
                <li class="timeline-item">
                    <div class="d-flex align-items-start gap-2">
                        <span class="timeline-dot {{ $item['scanned'] ? 'active' : '' }}"></span>
                        <div>
                            <div class="fw-bold">{{ $item['label'] }}</div>
                            <small class="muted">{{ $item['scanned'] ? 'Recorded successfully' : 'Pending scan' }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">{{ $item['time'] }}</div>
                        <small class="muted">{{ $item['scanned'] ? 'GPS verified' : '-' }}</small>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>

    @if($latestPayroll)
        <section class="payroll-mini mb-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div>
                    <small class="muted d-block">Latest Payroll</small>
                    <strong>{{ $latestPayroll->period_start->format('F Y') }}</strong>
                </div>
                <div class="text-end">
                    <small class="muted d-block">Net Salary</small>
                    <strong>${{ number_format($latestPayroll->net_salary, 2) }}</strong>
                </div>
            </div>
        </section>
    @endif
</x-layouts.employee>
