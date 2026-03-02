<x-layouts.employee page-title="Dashboard" :showPageBanner="false">
    @php
        $statusMap = [
            'Present'  => ['gradient'=>'linear-gradient(135deg,#059669,#0f766e)', 'msg'=>"Great! You're present today."],
            'Late'     => ['gradient'=>'linear-gradient(135deg,#d97706,#b45309)', 'msg'=>'You arrived a bit late today.'],
            'Absent'   => ['gradient'=>'linear-gradient(135deg,#dc2626,#b91c1c)', 'msg'=>'No attendance recorded today.'],
            'On Leave' => ['gradient'=>'linear-gradient(135deg,#2563eb,#1d4ed8)', 'msg'=>"You're on approved leave."],
        ];
        $s = $statusMap[$todayStatus] ?? ['gradient'=>'linear-gradient(135deg,#475569,#334155)', 'msg'=>'Status not yet determined.'];
    @endphp

    <style>
        .hero-card {
            border-radius: 22px;
            padding: 1.5rem 1.4rem;
            margin-bottom: 1.1rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .hero-card::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .hero-card::after {
            content: '';
            position: absolute;
            bottom: -30px; left: -20px;
            width: 130px; height: 130px;
            background: rgba(0,0,0,0.08);
            border-radius: 50%;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8; margin: 0; }
        .hero-status { font-family: 'Sora','Inter',sans-serif; font-size: 2.1rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1.1; margin: 0.3rem 0 0.4rem; }
        .hero-msg { font-size: 0.83rem; opacity: 0.88; line-height: 1.5; margin: 0; }
        .hero-date { font-size: 0.74rem; opacity: 0.7; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px; }

        .qs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.7rem; margin-bottom: 1.1rem; }
        @media (min-width: 480px) { .qs-grid { grid-template-columns: repeat(4, 1fr); } }

        .qs-card {
            background: #fff;
            border: 1px solid #dce8f6;
            border-radius: 16px;
            padding: 1rem 0.8rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(13,31,53,0.06);
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .qs-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(13,31,53,0.1); }
        .qs-icon { font-size: 1.35rem; line-height: 1; margin-bottom: 0.4rem; }
        .qs-val { font-family:'Sora','Inter',sans-serif; font-size: 1.75rem; font-weight: 800; line-height: 1; letter-spacing: -0.04em; }
        .qs-lbl { font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7d90; margin-top: 0.3rem; }

        .quicklink-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem; margin-bottom: 1.1rem; }
        .quicklink {
            background: #fff;
            border: 1px solid #dce8f6;
            border-radius: 14px;
            padding: 0.95rem 1rem;
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none;
            color: #0d1f35;
            font-weight: 600; font-size: 0.83rem;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            box-shadow: 0 2px 8px rgba(13,31,53,0.05);
        }
        .quicklink:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13,31,53,0.1); border-color: #a8cbe8; color: #1a3255; }
        .ql-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ql-icon svg { width: 18px; height: 18px; }

        .section-head { display: flex; justify-content: space-between; align-items: center; padding: 0.9rem 1rem 0.75rem; border-bottom: 1px solid #edf2f8; }
        .section-head h3 { font-family:'Sora','Inter',sans-serif; font-size: 0.87rem; font-weight: 700; margin: 0; color: #0d1f35; display:flex;align-items:center;gap:6px; }

        .tl-wrap { padding: 0.75rem 1rem; }
        .tl-item { display: flex; gap: 0.8rem; align-items: flex-start; padding: 0.55rem 0; position: relative; }
        .tl-item:not(:last-child)::after {
            content: ''; position: absolute;
            left: 14px; top: 36px;
            width: 2px; height: calc(100% - 14px);
            background: #e5edf6;
        }
        .tl-dot { width: 30px; height: 30px; flex-shrink: 0; border-radius: 9px; display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; }
        .tl-dot svg { width: 14px; height: 14px; }
        .tl-dot.in  { background: #dcfce7; color: #15803d; }
        .tl-dot.out { background: #fef3c7; color: #b45309; }
        .tl-meta { flex: 1; padding-top: 0.15rem; }
        .tl-type { font-size: 0.82rem; font-weight: 700; color: #0d1f35; }
        .tl-time { font-size: 0.72rem; color: #7a8fa4; margin-top: 0.08rem; }

        .payslip-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 1rem; border-bottom: 1px solid #f0f5fa; }
        .payslip-row:last-child { border-bottom: 0; }
        .payslip-row .pr-label { font-size: 0.79rem; color: #546270; }
        .payslip-row .pr-val { font-size: 0.82rem; font-weight: 700; color: #0d1f35; }

        @media (min-width: 768px) {
            .bottom-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
        }
    </style>

    {{-- ── HERO STATUS ── --}}
    <div class="hero-card" style="background: {{ $s['gradient'] }}">
        <div class="hero-content d-flex justify-content-between align-items-start">
            <div>
                <p class="hero-label">Today's Status</p>
                <h2 class="hero-status">{{ $todayStatus }}</h2>
                <p class="hero-msg">{{ $s['msg'] }}</p>
                <p class="hero-date">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-.5 5h1v6l5.25 3.15-.75 1.23L11 14V7z"/></svg>
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
            <a href="{{ route('employee.attendance.scan') }}"
               style="background:rgba(255,255,255,0.18);border:1.5px solid rgba(255,255,255,0.3);border-radius:12px;padding:.6rem .9rem;color:#fff;text-decoration:none;font-size:.78rem;font-weight:700;white-space:nowrap;backdrop-filter:blur(6px);flex-shrink:0;display:inline-flex;align-items:center;gap:5px;transition:background .2s"
               onmouseover="this.style.background='rgba(255,255,255,0.28)'" onmouseout="this.style.background='rgba(255,255,255,0.18)'">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/></svg>
                Scan Now
            </a>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="qs-grid">
        <div class="qs-card">
            <div class="qs-icon">📅</div>
            <div class="qs-val" style="color:#0d7a47">{{ $presentDays }}</div>
            <div class="qs-lbl">Present</div>
        </div>
        <div class="qs-card">
            <div class="qs-icon">⏰</div>
            <div class="qs-val" style="color:#c07900">{{ $lateDays }}</div>
            <div class="qs-lbl">Late</div>
        </div>
        <div class="qs-card">
            <div class="qs-icon">🚫</div>
            <div class="qs-val" style="color:#b53535">{{ $absentDays }}</div>
            <div class="qs-lbl">Absent</div>
        </div>
        <div class="qs-card">
            <div class="qs-icon">🌴</div>
            <div class="qs-val" style="color:#285f9c">{{ $leaveTaken }}</div>
            <div class="qs-lbl">Leave</div>
        </div>
    </div>

    {{-- ── QUICK LINKS ── --}}
    <div class="quicklink-grid">
        <a href="{{ route('employee.attendance.scan') }}" class="quicklink">
            <div class="ql-icon" style="background:#e0f2fe;color:#0369a1">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/></svg>
            </div>
            Scan QR
        </a>
        <a href="{{ route('employee.leave.index') }}" class="quicklink">
            <div class="ql-icon" style="background:#fef3c7;color:#b45309">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16h16V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            Request Leave
        </a>
        <a href="{{ route('employee.attendance.index') }}" class="quicklink">
            <div class="ql-icon" style="background:#ede9fe;color:#6d28d9">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            Calendar
        </a>
        <a href="{{ route('employee.salary.index') }}" class="quicklink">
            <div class="ql-icon" style="background:#dcfce7;color:#15803d">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            My Salary
        </a>
    </div>

    {{-- ── BOTTOM CARDS ── --}}
    <div class="bottom-2col d-grid gap-3 pb-5">

        {{-- Timeline --}}
        <div class="app-card overflow-hidden">
            <div class="section-head">
                <h3>
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Today's Timeline
                </h3>
                <span style="font-size:.71rem;color:#8294a8;font-weight:600">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="tl-wrap">
                @php $scannedLogs = collect($timelineLogs ?? [])->filter(fn($l) => $l['scanned']); @endphp
                @if($scannedLogs->count() > 0)
                    @foreach($scannedLogs as $log)
                    <div class="tl-item">
                        @php $isIn = str_contains(strtolower($log['label']), 'in'); @endphp
                        <div class="tl-dot {{ $isIn ? 'in' : 'out' }}">
                            @if($isIn)
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                            @endif
                        </div>
                        <div class="tl-meta">
                            <div class="tl-type">{{ $log['label'] }}</div>
                            <div class="tl-time">{{ $log['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <div style="width:46px;height:46px;border-radius:13px;background:#f0f7ff;margin:0 auto .8rem;display:flex;align-items:center;justify-content:center">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#4b90d9" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <p style="font-size:.82rem;color:#6b7d90;margin:0 0 .5rem">No attendance recorded yet.</p>
                        <a href="{{ route('employee.attendance.scan') }}" class="btn-brand" style="font-size:.77rem;padding:.48rem .9rem">Scan Now</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Latest Payslip --}}
        @if(isset($payroll))
        <div class="app-card overflow-hidden">
            <div class="section-head">
                <h3>
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Latest Payslip
                </h3>
                <span style="font-size:.7rem;font-weight:700;padding:.22rem .55rem;border-radius:7px;{{ $payroll->status==='paid' ? 'background:#dcfce7;color:#166534' : 'background:#fef9c3;color:#854d0e' }}">
                    {{ ucfirst($payroll->status) }}
                </span>
            </div>
            <div class="payslip-row">
                <span class="pr-label">Period</span>
                <span class="pr-val">{{ $payroll->month }} {{ $payroll->year }}</span>
            </div>
            <div class="payslip-row">
                <span class="pr-label">Net Pay</span>
                <span class="pr-val" style="font-size:.98rem;color:#0d7a47">{{ number_format($payroll->net_salary ?? 0, 2) }} {{ $payroll->currency ?? '$' }}</span>
            </div>
            <div class="payslip-row" style="border:0;gap:.5rem">
                <a href="{{ route('employee.salary.index') }}" class="btn-quiet" style="font-size:.77rem;padding:.5rem .85rem">View All</a>
                <a href="{{ route('employee.salary.download', $payroll) }}" class="btn-brand" style="font-size:.77rem;padding:.5rem .9rem">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PDF
                </a>
            </div>
        </div>
        @endif
    </div>
</x-layouts.employee>