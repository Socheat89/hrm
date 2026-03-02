<x-layouts.employee page-title="My Salary" page-description="Review monthly payroll, status, and downloadable salary slips.">
    <style>
        .salary-hero {
            border-radius: 20px;
            padding: 1.4rem 1.3rem;
            margin-bottom: 1.2rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d6ec0 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(20,50,90,0.22);
        }
        .salary-hero::before {
            content:'';position:absolute;top:-40px;right:-40px;
            width:150px;height:150px;background:rgba(255,255,255,0.08);border-radius:50%;
        }
        .salary-hero::after {
            content:'';position:absolute;bottom:-25px;left:10px;
            width:100px;height:100px;background:rgba(255,255,255,0.05);border-radius:50%;
        }
        .salary-hero-inner { position:relative;z-index:1; }
        .salary-hero small { font-size:.72rem;font-weight:600;opacity:.75;text-transform:uppercase;letter-spacing:.07em; }
        .salary-hero h2 { font-family:'Sora','Inter',sans-serif;font-size:2rem;font-weight:800;letter-spacing:-.04em;margin:.2rem 0 .1rem; }
        .salary-hero p { font-size:.8rem;opacity:.8;margin:0; }

        .payroll-list { display:grid;gap:.75rem; }
        .payroll-item {
            background:#fff;
            border:1px solid #dce8f6;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 2px 8px rgba(13,31,53,.06);
            transition:transform .18s,box-shadow .18s;
        }
        .payroll-item:hover { transform:translateY(-2px);box-shadow:0 8px 22px rgba(13,31,53,.11); }
        .payroll-item-header {
            display:flex;justify-content:space-between;align-items:flex-start;
            padding:.9rem 1rem;gap:.75rem;
        }
        .payroll-period { font-family:'Sora','Inter',sans-serif;font-size:.92rem;font-weight:700;color:#0d1f35;margin:0 0 .2rem; }
        .payroll-net { font-family:'Sora','Inter',sans-serif;font-size:1.35rem;font-weight:800;color:#0d7a47;letter-spacing:-.03em;margin:0; }
        .payroll-net-label { font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#7a8fa4;margin:0 0 .1rem; }

        .pay-status {
            border-radius:8px;font-size:.7rem;font-weight:700;
            padding:.26rem .6rem;border:1px solid transparent;
            text-transform:uppercase;letter-spacing:.04em;flex-shrink:0;
        }
        .pay-status.paid    { background:#dcfce7;color:#166534;border-color:#bbf7d0; }
        .pay-status.pending { background:#fef9c3;color:#854d0e;border-color:#fef08a; }

        .payroll-item-footer {
            border-top:1px solid #f0f5fa;
            padding:.65rem 1rem;
            display:flex;gap:.5rem;align-items:center;
        }

        .modal-salary .modal-content {
            border:1px solid #d5e3f5;
            border-radius:18px;
            overflow:hidden;
        }
        .modal-salary .modal-header {
            background: linear-gradient(135deg,#1e3a5f,#2d6ec0);
            color:#fff;border-bottom:0;padding:1rem 1.2rem;
        }
        .modal-salary .modal-header .btn-close { filter:invert(1) brightness(2); }
        .modal-salary .modal-title { font-family:'Sora','Inter',sans-serif;font-weight:700;font-size:.95rem; }
        .modal-salary .modal-body { padding:0; }
        .pay-detail-row {
            display:flex;justify-content:space-between;align-items:center;
            padding:.7rem 1.2rem;border-bottom:1px solid #f0f5fa;font-size:.85rem;
        }
        .pay-detail-row:last-child { border:0; }
        .pay-detail-row.total {
            background:#f7fbff;font-weight:700;
            border-top:2px solid #dce8f6;font-size:.9rem;color:#0d7a47;
        }
        .pay-detail-row span { color:#546270; }
        .pay-detail-row strong { color:#0d1f35; }
        .empty-state {
            text-align:center;padding:3rem 1rem;
            border:1px dashed #c5d8f0;border-radius:16px;background:#f8fbff;
        }
    </style>

    {{-- ── SALARY HERO ── --}}
    <div class="salary-hero">
        <div class="salary-hero-inner d-flex justify-content-between align-items-end">
            <div>
                <small>Base Salary</small>
                <h2>${{ number_format($baseSalary, 2) }}</h2>
                <p>Configured base · before bonus &amp; deductions</p>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <small>Payslips</small>
                <div style="font-family:'Sora','Inter',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-0.04em;line-height:1">{{ $payrolls->total() }}</div>
                <small>total records</small>
            </div>
        </div>
    </div>

    {{-- ── PAYROLL LIST ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Payroll History</h2>
        <span class="badge-soft">{{ $payrolls->total() }} payslips</span>
    </div>

    <div class="payroll-list mb-4">
        @forelse($payrolls as $payroll)
        <article class="payroll-item">
            <div class="payroll-item-header">
                <div>
                    <p class="payroll-period">{{ $payroll->period_start->format('F Y') }}</p>
                    <p class="payroll-net-label">Net Salary</p>
                    <p class="payroll-net">${{ number_format($payroll->net_salary, 2) }}</p>
                </div>
                <span class="pay-status {{ $payroll->status === 'paid' ? 'paid' : 'pending' }}">{{ $payroll->status }}</span>
            </div>
            <div class="payroll-item-footer">
                <button type="button" class="btn-quiet" style="font-size:.77rem;padding:.48rem .85rem"
                    data-bs-toggle="modal" data-bs-target="#salaryModal{{ $payroll->id }}">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail
                </button>
                <a href="{{ route('employee.salary.download', $payroll) }}" class="btn-brand" style="font-size:.77rem;padding:.48rem .9rem">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PDF
                </a>
            </div>
        </article>

        {{-- Modal --}}
        <div class="modal fade modal-salary" id="salaryModal{{ $payroll->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $payroll->period_start->format('F Y') }} — Payslip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach($payroll->items as $item)
                        <div class="pay-detail-row">
                            <span>{{ $item->label }}</span>
                            <strong>${{ number_format($item->amount, 2) }}</strong>
                        </div>
                        @endforeach
                        <div class="pay-detail-row">
                            <span>Bonus</span>
                            <strong>${{ number_format($payroll->bonus, 2) }}</strong>
                        </div>
                        <div class="pay-detail-row">
                            <span>Other Deductions</span>
                            <strong style="color:#b53535">-${{ number_format($payroll->other_deduction, 2) }}</strong>
                        </div>
                        <div class="pay-detail-row total">
                            <span style="color:#0d7a47;font-weight:800">Net Salary</span>
                            <strong style="font-size:1.05rem">${{ number_format($payroll->net_salary, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#a0b4c8" stroke-width="1.5" style="margin-bottom:.8rem"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <p style="font-size:.85rem;color:#6b7d90;margin:0">No payroll records yet.</p>
        </div>
        @endforelse
    </div>

    <div class="mb-3">{{ $payrolls->links() }}</div>
</x-layouts.employee>
