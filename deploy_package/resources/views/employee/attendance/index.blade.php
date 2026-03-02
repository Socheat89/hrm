<x-layouts.employee page-title="My Attendance" page-description="Check your daily records, month summary, and detailed scan logs.">
    @php
        $carbonMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;
        $firstWeekday = $carbonMonth->dayOfWeek;
        $today = now()->toDateString();

        $colorByStatus = [
            'present' => 'calendar-present',
            'late' => 'calendar-late',
            'absent' => 'calendar-absent',
            'leave' => 'calendar-leave',
            'future' => 'calendar-future',
        ];
    @endphp

    <style>
        .calendar-wrap {
            overflow-x: auto;
            padding-bottom: 0.2rem;
        }

        .calendar-weekdays,
        .calendar-grid {
            min-width: 680px;
            display: grid;
            grid-template-columns: repeat(7, minmax(84px, 1fr));
            gap: 0.45rem;
        }

        .calendar-weekdays div {
            text-align: center;
            font-size: 0.76rem;
            font-weight: 700;
            color: #5a708a;
            background: #edf4fd;
            border: 1px solid #d4e1f1;
            border-radius: 10px;
            padding: 0.42rem 0.2rem;
        }

        .calendar-empty {
            border-radius: 12px;
            border: 1px dashed #d8e2ef;
            background: #f8fbff;
            min-height: 70px;
        }

        .calendar-day {
            border-radius: 12px;
            border: 1px solid #d9e3f0;
            min-height: 70px;
            padding: 0.42rem 0.5rem;
            text-align: left;
            font-size: 0.73rem;
            line-height: 1.15;
            font-weight: 700;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .calendar-day:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(17, 55, 89, 0.12);
        }

        .calendar-day .day-number {
            font-size: 0.85rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .calendar-day .day-status {
            text-transform: capitalize;
            color: #4e6178;
        }

        .calendar-present {
            background: #ecfbf2;
            border-color: #bee7ca;
            color: #1f7245;
        }

        .calendar-late {
            background: #fff8ea;
            border-color: #f2d79b;
            color: #8a5b00;
        }

        .calendar-absent {
            background: #fff1f1;
            border-color: #f1c5c5;
            color: #a03939;
        }

        .calendar-leave {
            background: #edf4ff;
            border-color: #c8dbf6;
            color: #295f9a;
        }

        .calendar-future {
            background: #fff;
            border-color: #d9e3f0;
            color: #607289;
        }

        .detail-sheet {
            position: fixed;
            inset: 0;
            z-index: 1120;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.22s ease;
        }

        .detail-sheet.open {
            opacity: 1;
            pointer-events: auto;
        }

        .detail-sheet-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(10, 24, 39, 0.46);
            backdrop-filter: blur(2px);
        }

        .detail-sheet-panel {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            max-height: 78vh;
            overflow-y: auto;
            background: #fff;
            border-radius: 20px 20px 0 0;
            border-top: 1px solid #d4dfee;
            transform: translateY(104%);
            transition: transform 0.22s ease;
            box-shadow: 0 -22px 40px rgba(11, 35, 61, 0.2);
        }

        .detail-sheet.open .detail-sheet-panel {
            transform: translateY(0);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        @media (min-width: 768px) {
            .summary-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
    </style>

    <section class="card-soft p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
                <div>
                    <label class="input-label d-block">Month</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                </div>
                <button class="btn-brand">Load</button>
            </form>
            <a href="{{ route('employee.attendance.export', ['month' => $month]) }}" class="btn-quiet">Export PDF</a>
        </div>
    </section>

    <section class="card-soft p-4 mb-4">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="status-pill status-present">Present</span>
            <span class="status-pill status-late">Late</span>
            <span class="status-pill status-absent">Absent</span>
            <span class="status-pill status-leave">Leave</span>
        </div>

        <div class="calendar-wrap">
            <div class="calendar-weekdays mb-2">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div class="calendar-grid">
                @for($i = 0; $i < $firstWeekday; $i++)
                    <div class="calendar-empty"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = $carbonMonth->copy()->day($day)->toDateString();
                        $record = $calendarData[$date] ?? null;
                        $status = $record['status'] ?? ($date <= $today ? 'absent' : 'future');
                        $color = $colorByStatus[$status] ?? $colorByStatus['future'];
                    @endphp
                    <button type="button" class="calendar-day {{ $color }} day-cell" data-date="{{ $date }}">
                        <div class="day-number">{{ $day }}</div>
                        <div class="day-status">{{ $status }}</div>
                    </button>
                @endfor
            </div>
        </div>
    </section>

    <section class="card-soft p-4 mb-5">
        <h3 class="section-title">Monthly Summary</h3>
        <div class="summary-grid text-center">
            <article class="stat-card">
                <small class="muted">Present</small>
                <div class="value">{{ $summary['present'] }}</div>
                <small class="muted">Days</small>
            </article>
            <article class="stat-card">
                <small class="muted">Late</small>
                <div class="value">{{ $summary['late'] }}</div>
                <small class="muted">Days</small>
            </article>
            <article class="stat-card">
                <small class="muted">Leave</small>
                <div class="value">{{ $summary['leave'] }}</div>
                <small class="muted">Days</small>
            </article>
            <article class="stat-card">
                <small class="muted">OT Hours</small>
                <div class="value">{{ $summary['overtime'] }}</div>
                <small class="muted">Total</small>
            </article>
        </div>
    </section>

    <div id="attendanceDetailSheet" class="detail-sheet" aria-hidden="true">
        <div class="detail-sheet-backdrop" data-close-detail></div>
        <div class="detail-sheet-panel">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h5 class="mb-0 fw-bold">Attendance Detail</h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-close-detail>Close</button>
            </div>
            <div class="p-4" id="detailBody"></div>
        </div>
    </div>

    <script>
        const calendarData = @json($calendarData);
        const today = '{{ $today }}';
        const detailSheet = document.getElementById('attendanceDetailSheet');
        const detailBody = document.getElementById('detailBody');

        function statusLabel(status) {
            return status
                .replace(/_/g, ' ')
                .replace(/\b\w/g, (char) => char.toUpperCase());
        }

        function renderDetail(date) {
            const data = calendarData[date] || {
                status: date <= today ? 'absent' : 'future',
                scans: {},
                work_hours: 0,
                late_minutes: 0,
                overtime_hours: 0,
                gps_status: 'N/A'
            };

            detailBody.innerHTML = `
                <div class="card-quiet p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <small class="muted d-block">Date</small>
                            <strong>${date}</strong>
                        </div>
                        <span class="badge-soft">${statusLabel(data.status)}</span>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6"><div class="card-quiet p-2"><small class="muted d-block">Work Hours</small><strong>${data.work_hours}</strong></div></div>
                    <div class="col-6"><div class="card-quiet p-2"><small class="muted d-block">Late Minutes</small><strong>${data.late_minutes}</strong></div></div>
                    <div class="col-6"><div class="card-quiet p-2"><small class="muted d-block">Overtime</small><strong>${data.overtime_hours}</strong></div></div>
                    <div class="col-6"><div class="card-quiet p-2"><small class="muted d-block">GPS</small><strong>${data.gps_status}</strong></div></div>
                </div>

                <h6 class="fw-bold mb-2">Scan Times</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between"><span>Morning In</span><strong>${data.scans?.['Morning In'] || '-'}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Lunch Out</span><strong>${data.scans?.['Lunch Out'] || '-'}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Lunch In</span><strong>${data.scans?.['Lunch In'] || '-'}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Evening Out</span><strong>${data.scans?.['Evening Out'] || '-'}</strong></li>
                </ul>
            `;
        }

        function openDetail(date) {
            renderDetail(date);
            detailSheet.classList.add('open');
            detailSheet.setAttribute('aria-hidden', 'false');
        }

        function closeDetail() {
            detailSheet.classList.remove('open');
            detailSheet.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.day-cell').forEach((item) => {
            item.addEventListener('click', () => openDetail(item.dataset.date));
        });

        document.querySelectorAll('[data-close-detail]').forEach((item) => {
            item.addEventListener('click', closeDetail);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && detailSheet.classList.contains('open')) {
                closeDetail();
            }
        });
    </script>
</x-layouts.employee>
