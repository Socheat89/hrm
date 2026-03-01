<x-layouts.employee page-title="My Salary" page-description="Review monthly payroll, status, and downloadable salary slips.">
    <style>
        .salary-summary {
            background: linear-gradient(145deg, #123a61 0%, #27588c 100%);
            color: #fff;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 22px 34px rgba(16, 45, 74, 0.25);
        }

        .salary-summary small {
            color: rgba(235, 243, 252, 0.85);
        }

        .payroll-card {
            border: 1px solid #d8e2ef;
            border-radius: 16px;
            background: #f8fbff;
            padding: 0.95rem;
        }

        .payroll-status {
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.24rem 0.56rem;
            border: 1px solid transparent;
            text-transform: capitalize;
        }

        .payroll-status.paid {
            color: #1d7245;
            background: #ecfbf2;
            border-color: #bfe7cd;
        }

        .payroll-status.pending {
            color: #8b5f00;
            background: #fff8ea;
            border-color: #f2d79b;
        }

        .modal-salary .modal-content {
            border: 1px solid #d5dfed;
            border-radius: 16px;
        }
    </style>

    <section class="salary-summary p-4 mb-4">
        <small class="d-block mb-1">Base Salary</small>
        <h2 class="fw-bold mb-1">${{ number_format($baseSalary, 2) }}</h2>
        <small>Configured base amount before bonus and deductions.</small>
    </section>

    <section class="card-soft p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">Payroll History</h3>
            <span class="badge-soft">{{ $payrolls->total() }} payslips</span>
        </div>

        <div class="d-grid gap-3">
            @forelse($payrolls as $payroll)
                <article class="payroll-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <h4 class="h6 mb-1 fw-bold">{{ $payroll->period_start->format('F Y') }}</h4>
                            <small class="muted">Net Salary</small>
                            <div class="h5 fw-bold mb-0 mt-1">${{ number_format($payroll->net_salary, 2) }}</div>
                        </div>
                        <span class="payroll-status {{ $payroll->status === 'paid' ? 'paid' : 'pending' }}">{{ $payroll->status }}</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <button type="button" class="btn-quiet" data-bs-toggle="modal" data-bs-target="#salaryModal{{ $payroll->id }}">View Detail</button>
                        <a href="{{ route('employee.salary.download', $payroll) }}" class="btn-brand">Download PDF</a>
                    </div>
                </article>

                <div class="modal fade modal-salary" id="salaryModal{{ $payroll->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title fw-bold">Payroll Detail - {{ $payroll->period_start->format('M Y') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <ul class="list-group list-group-flush">
                                    @foreach($payroll->items as $item)
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span>{{ $item->label }}</span>
                                            <strong>${{ number_format($item->amount, 2) }}</strong>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span>Bonus</span>
                                        <strong>${{ number_format($payroll->bonus, 2) }}</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span>Other Deduction</span>
                                        <strong>${{ number_format($payroll->other_deduction, 2) }}</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold">
                                        <span>Net Salary</span>
                                        <span>${{ number_format($payroll->net_salary, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center muted py-4">No payroll records yet.</div>
            @endforelse
        </div>
    </section>

    <div class="mb-3">{{ $payrolls->links() }}</div>
</x-layouts.employee>
