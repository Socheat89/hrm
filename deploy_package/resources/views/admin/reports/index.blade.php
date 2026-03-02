<x-layouts.admin>
    <h5 class="mb-3">Reports</h5>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="card card-soft p-3"><small class="text-secondary">Monthly Payroll</small><h4>${{ number_format($monthlyPayroll,2) }}</h4></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card card-soft p-3">
                <h6>Attendance by Branch</h6>
                <table class="table table-sm"><thead><tr><th>Branch</th><th class="text-end">Total</th></tr></thead><tbody>
                    @foreach($attendanceByBranch as $row)
                        <tr><td>{{ $row->branch?->name ?? 'N/A' }}</td><td class="text-end">{{ $row->total }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-soft p-3">
                <h6>Leave by Type</h6>
                <table class="table table-sm"><thead><tr><th>Leave Type</th><th class="text-end">Total</th></tr></thead><tbody>
                    @foreach($leaveByType as $row)
                        <tr><td>{{ $row->leaveType?->name ?? 'N/A' }}</td><td class="text-end">{{ $row->total }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
        </div>
    </div>
</x-layouts.admin>
