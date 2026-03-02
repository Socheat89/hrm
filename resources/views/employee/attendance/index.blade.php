<x-layouts.employee page-title="My Attendance" page-description="Check your daily records, month summary, and detailed scan logs.">
    @php
        $carbonMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;
        $firstWeekday = $carbonMonth->dayOfWeek;
        $today = now()->toDateString();

        $colorByStatus = [
            'present' => 'cs-present',
            'late'    => 'cs-late',
            'absent'  => 'cs-absent',
            'leave'   => 'cs-leave',
            'future'  => 'cs-future',
        ];
    @endphp

    <style>
        /* ── Month nav ─────────────────────────────────── */
        .month-nav {
            display: flex; align-items: center; gap: .6rem; flex-wrap: wrap;
        }
        .month-nav input[type="month"] {
            border: 1.5px solid #c8ddf0; border-radius: 12px;
            padding: .48rem .75rem; font-size: .88rem; font-weight: 600;
            color: #1a2d42; background: #fff; cursor: pointer;
        }
        .month-nav input[type="month"]:focus { outline: none; border-color: var(--brand); }

        /* ── Legend pills ───────────────────────────────── */
        .legend-pill {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: .71rem; font-weight: 700; padding: .28rem .65rem;
            border-radius: 999px; border: 1px solid transparent;
            letter-spacing: .02em;
        }
        .legend-dot { width: 7px; height: 7px; border-radius: 50%; }

        /* ── Calendar ───────────────────────────────────── */
        .calendar-wrap { overflow-x: auto; padding-bottom: .2rem; }

        .calendar-weekdays,
        .calendar-grid {
            min-width: 600px;
            display: grid;
            grid-template-columns: repeat(7, minmax(78px, 1fr));
            gap: .38rem;
        }

        .calendar-weekdays div {
            text-align: center; font-size: .71rem; font-weight: 800;
            color: #6b7d90; background: #f0f6fd;
            border: 1px solid #dce8f6; border-radius: 10px;
            padding: .38rem .2rem; letter-spacing: .05em; text-transform: uppercase;
        }

        .calendar-empty {
            border-radius: 13px; border: 1px dashed #dce8f6;
            background: #f8fbff; min-height: 68px;
        }

        .calendar-day {
            border-radius: 13px; min-height: 68px;
            padding: .42rem .5rem; text-align: left;
            font-size: .71rem; font-weight: 700;
            transition: transform .15s, box-shadow .15s;
            cursor: pointer; background: #fff;
            border: 1.5px solid #dce8f6;
        }
        .calendar-day:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(13,31,53,.13); }
        .day-number { font-size: .88rem; font-weight: 800; margin-bottom: 3px; }
        .day-status { font-size: .65rem; text-transform: capitalize; font-weight: 700; opacity: .9; }
        .day-today { box-shadow: 0 0 0 2.5px var(--brand); }

        /* Status color tokens */
        .cs-present { background: #ecfcf2; border-color: #6ee7a7; color: #166534; }
        .cs-late     { background: #fffbea; border-color: #fce787; color: #92400e; }
        .cs-absent   { background: #fff1f1; border-color: #fca5a5; color: #991b1b; }
        .cs-leave    { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
        .cs-future   { background: #fafbfc; border-color: #dce8f6; color: #7a8fa4; }

        /* ── Summary cards ──────────────────────────────── */
        .att-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .75rem;
        }
        @media(min-width:576px) { .att-summary-grid { grid-template-columns: repeat(4, 1fr); } }

        .att-stat {
            border-radius: 16px; border: 1px solid var(--ln);
            background: #fff; padding: 1rem .9rem; text-align: center;
            box-shadow: 0 2px 8px rgba(13,31,53,.05);
            transition: transform .18s, box-shadow .18s;
        }
        .att-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(13,31,53,.1); }
        .att-stat .att-icon { font-size: 1.45rem; margin-bottom: .35rem; }
        .att-stat .att-val { font-family: 'Sora','Inter',sans-serif; font-size: 1.55rem; font-weight: 800; line-height: 1; color: #0d1f35; }
        .att-stat .att-label { font-size: .7rem; font-weight: 700; color: #6b7d90; margin-top: .25rem; text-transform: uppercase; letter-spacing: .04em; }

        /* ── Detail sheet ───────────────────────────────── */
        .att-sheet {
            position: fixed; inset: 0; z-index: 1120;
            opacity: 0; pointer-events: none;
            transition: opacity .22s ease;
        }
        .att-sheet.open { opacity: 1; pointer-events: auto; }

        .att-sheet-backdrop {
            position: absolute; inset: 0;
            background: rgba(8,20,36,.55);
            backdrop-filter: blur(4px) saturate(120%);
        }

        .att-sheet-panel {
            position: absolute; left: 0; right: 0; bottom: 0;
            max-height: 82vh; overflow-y: auto;
            background: #fff; border-radius: 22px 22px 0 0;
            border-top: 1px solid #d4dfee;
            transform: translateY(105%);
            transition: transform .24s cubic-bezier(.32,1,.28,1);
            box-shadow: 0 -22px 50px rgba(8,22,44,.22);
        }
        .att-sheet.open .att-sheet-panel { transform: translateY(0); }

        .att-sheet-handle {
            width: 44px; height: 4px; border-radius: 99px;
            background: #c8d8ea; margin: 10px auto 0; cursor: pointer;
        }

        .sheet-date-badge {
            background: linear-gradient(135deg,#1e3a5f,#2d6ec0);
            color: #fff; border-radius: 14px; padding: .9rem 1rem;
            margin-bottom: 1rem;
        }
        .sheet-date-badge .sd-date { font-family:'Sora','Inter',sans-serif; font-size:1.15rem; font-weight:800; }
        .sheet-date-badge .sd-status { font-size:.75rem; font-weight:700; opacity:.8; margin-top:.2rem; }

        .att-detail-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: .5rem 0; border-bottom: 1px solid #f0f5fa;
            font-size: .82rem;
        }
        .att-detail-row:last-child { border-bottom: 0; }
        .att-detail-row span { color: #6b7d90; font-weight: 500; }
        .att-detail-row strong { color: #1a2d42; font-weight: 700; }

        .scan-list { list-style: none; margin: 0; padding: 0; }
        .scan-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: .52rem .75rem; border-radius: 11px;
            background: #f7fafd; border: 1px solid #e6eff8;
            margin-bottom: .4rem; font-size: .8rem;
        }
        .scan-item .scan-label { color: #546270; font-weight: 600; }
        .scan-item .scan-time { color: #0d1f35; font-weight: 800; font-family:'Sora','Inter',sans-serif; }
        .scan-item .scan-time.empty { color: #b0c0d0; font-weight: 500; }

        .status-chip {
            border-radius: 8px; font-size: .7rem; font-weight: 800;
            padding: .25rem .6rem; text-transform: capitalize;
            letter-spacing: .03em;
        }
        .chip-present { background:#dcfce7;color:#166534; }
        .chip-late    { background:#fef9c3;color:#92400e; }
        .chip-absent  { background:#fee2e2;color:#991b1b; }
        .chip-leave   { background:#dbeafe;color:#1d4ed8; }
        .chip-future  { background:#f1f5f9;color:#64748b; }
    </style>

    {{-- ── Month navigation + export ── --}}
    <section class="card-soft p-3 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <form method="GET" class="month-nav">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#4e86be" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
                <input type="month" name="month" value="{{ $month }}" autofocus>
                <button class="btn-brand" type="submit" style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;padding:.48rem .9rem">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    Load
                </button>
            </form>
            <a href="{{ route('employee.attendance.export', ['month' => $month]) }}" class="btn-quiet" style="display:inline-flex;align-items:center;gap:5px;font-size:.8rem;padding:.46rem .85rem">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </section>

    {{-- ── Calendar ── --}}
    <section class="card-soft p-3 mb-4">
        {{-- Legend --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="legend-pill" style="background:#ecfcf2;border-color:#6ee7a7;color:#166534"><span class="legend-dot" style="background:#22c55e"></span>Present</span>
            <span class="legend-pill" style="background:#fffbea;border-color:#fce787;color:#92400e"><span class="legend-dot" style="background:#eab308"></span>Late</span>
            <span class="legend-pill" style="background:#fff1f1;border-color:#fca5a5;color:#991b1b"><span class="legend-dot" style="background:#ef4444"></span>Absent</span>
            <span class="legend-pill" style="background:#eff6ff;border-color:#93c5fd;color:#1d4ed8"><span class="legend-dot" style="background:#3b82f6"></span>Leave</span>
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
                        $date   = $carbonMonth->copy()->day($day)->toDateString();
                        $record = $calendarData[$date] ?? null;
                        $status = $record['status'] ?? ($date <= $today ? 'absent' : 'future');
                        $color  = $colorByStatus[$status] ?? $colorByStatus['future'];
                        $isToday = $date === $today;
                    @endphp
                    <button type="button"
                            class="calendar-day {{ $color }} day-cell{{ $isToday ? ' day-today' : '' }}"
                            data-date="{{ $date }}">
                        <div class="day-number">{{ $day }}{{ $isToday ? ' ·' : '' }}</div>
                        <div class="day-status">{{ $status }}</div>
                    </button>
                @endfor
            </div>
        </div>
    </section>

    {{-- ── Monthly Summary ── --}}
    <section class="mb-4">
        <h2 class="section-title">Monthly Summary</h2>
        <div class="att-summary-grid">
            <article class="att-stat">
                <div class="att-icon">✅</div>
                <div class="att-val" style="color:#166534">{{ $summary['present'] }}</div>
                <div class="att-label">Present</div>
            </article>
            <article class="att-stat">
                <div class="att-icon">⏰</div>
                <div class="att-val" style="color:#92400e">{{ $summary['late'] }}</div>
                <div class="att-label">Late</div>
            </article>
            <article class="att-stat">
                <div class="att-icon">📅</div>
                <div class="att-val" style="color:#1d4ed8">{{ $summary['leave'] }}</div>
                <div class="att-label">Leave</div>
            </article>
            <article class="att-stat">
                <div class="att-icon">💼</div>
                <div class="att-val" style="color:#var(--brand)">{{ $summary['overtime'] }}</div>
                <div class="att-label">OT Hours</div>
            </article>
        </div>
    </section>

    <div class="pb-5"></div>

    {{-- ── Detail Sheet ── --}}
    <div id="attDetailSheet" class="att-sheet" aria-hidden="true">
        <div class="att-sheet-backdrop" data-close-detail></div>
        <div class="att-sheet-panel">
            <div class="att-sheet-handle" data-close-detail></div>
            <div class="p-4">
                <div id="attDetailBody"></div>
            </div>
        </div>
    </div>

    <script>
        const calendarData = @json($calendarData);
        const today = '{{ $today }}';
        const attSheet = document.getElementById('attDetailSheet');
        const attBody  = document.getElementById('attDetailBody');

        const statusChip = {
            present : 'chip-present',
            late    : 'chip-late',
            absent  : 'chip-absent',
            leave   : 'chip-leave',
            future  : 'chip-future',
        };

        function fmtStatus(s) {
            return s.replace(/_/g,' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        function metaRow(label, value) {
            return `<div class="att-detail-row"><span>${label}</span><strong>${value}</strong></div>`;
        }

        function renderDetail(date) {
            const d = calendarData[date] || {
                status: date <= today ? 'absent' : 'future',
                scans: {}, work_hours: 0, late_minutes: 0,
                overtime_hours: 0, gps_status: 'N/A'
            };
            const chipCls = statusChip[d.status] || 'chip-future';
            const dateObj  = new Date(date);
            const dateStr  = dateObj.toLocaleDateString('en-US',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

            const scanKeys = ['Morning In','Lunch Out','Lunch In','Evening Out'];
            const scanHtml = scanKeys.map(k => {
                const t = (d.scans && d.scans[k]) || null;
                return `<li class="scan-item">
                    <span class="scan-label">${k}</span>
                    <span class="scan-time${t?'':' empty'}">${t || '—'}</span>
                </li>`;
            }).join('');

            attBody.innerHTML = `
                <div class="sheet-date-badge">
                    <div class="sd-date">${dateStr}</div>
                    <div class="sd-status"><span class="status-chip ${chipCls}" style="background:rgba(255,255,255,.18);color:#fff">${fmtStatus(d.status)}</span></div>
                </div>

                <div class="card-soft p-3 mb-3">
                    ${metaRow('Work Hours', d.work_hours + ' hrs')}
                    ${metaRow('Late Minutes', d.late_minutes + ' min')}
                    ${metaRow('Overtime', d.overtime_hours + ' hrs')}
                    ${metaRow('GPS Status', d.gps_status || 'N/A')}
                </div>

                <p class="section-title mb-2">Scan Times</p>
                <ul class="scan-list">${scanHtml}</ul>
            `;
        }

        function openDetail(date) {
            renderDetail(date);
            attSheet.classList.add('open');
            attSheet.setAttribute('aria-hidden','false');
        }

        function closeDetail() {
            attSheet.classList.remove('open');
            attSheet.setAttribute('aria-hidden','true');
        }

        document.querySelectorAll('.day-cell').forEach(el => {
            el.addEventListener('click', () => openDetail(el.dataset.date));
        });
        document.querySelectorAll('[data-close-detail]').forEach(el => {
            el.addEventListener('click', closeDetail);
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && attSheet.classList.contains('open')) closeDetail();
        });
    </script>
</x-layouts.employee>
