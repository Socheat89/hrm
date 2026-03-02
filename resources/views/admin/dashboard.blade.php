<x-layouts.admin>
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">Dashboard Overview</h2>
                <p class="mt-1 text-sm text-slate-500">Daily snapshot of attendance, leave activity, and payroll impact.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <small class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Employees</small>
            <h4 class="mt-2 text-3xl font-bold text-slate-800">{{ $totalEmployees }}</h4>
        </div>
        <div class="rounded-2xl border border-green-200 bg-white p-5 shadow-sm">
            <small class="text-xs font-semibold uppercase tracking-wider text-slate-500">Present Today</small>
            <h4 class="mt-2 text-3xl font-bold text-green-600">{{ $todayAttendance }}</h4>
        </div>
        <div class="rounded-2xl border border-orange-200 bg-white p-5 shadow-sm">
            <small class="text-xs font-semibold uppercase tracking-wider text-slate-500">Late Today</small>
            <h4 class="mt-2 text-3xl font-bold text-orange-500">{{ $lateEmployeesCount }}</h4>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-sm">
            <small class="text-xs font-semibold uppercase tracking-wider text-slate-500">Leave Today</small>
            <h4 class="mt-2 text-3xl font-bold text-blue-500">{{ $onLeaveToday }}</h4>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-slate-900 p-5 shadow-sm sm:col-span-2 xl:col-span-1">
            <small class="text-xs font-semibold uppercase tracking-wider text-slate-300">Monthly Payroll</small>
            <h4 class="mt-2 text-3xl font-bold text-white">${{ number_format($monthlyPayrollCost,2) }}</h4>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Chart -->
        <div class="lg:col-span-2">
            <div class="h-full rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h6 class="text-lg font-bold text-slate-800">Monthly Attendance</h6>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">This Month</span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="attendanceChart"></canvas>
                </div>
                @unless($hasMonthlyAttendanceData)
                    <p class="mt-3 text-sm text-slate-500">No attendance records yet for this month.</p>
                @endunless
            </div>
        </div>
        
        <!-- Late Employees -->
        <div class="lg:col-span-1">
            <div class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5">
                    <h6 class="text-lg font-bold text-slate-800">Late Employees Today</h6>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-5 py-3 font-medium">Employee</th>
                                <th class="px-5 py-3 font-medium text-right">Late (min)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($lateEmployees as $row)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $row->employee->user->name }}</td>
                                <td class="px-5 py-3 text-right text-orange-600 font-semibold">{{ $row->late_minutes }}m</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-5 py-8 text-center text-slate-500 text-sm">No late records today</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pending Leaves -->
        <div class="lg:col-span-3">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5">
                    <h6 class="text-lg font-bold text-slate-800">Pending Leave Requests</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-medium">Employee</th>
                                <th class="px-6 py-3 font-medium">Type</th>
                                <th class="px-6 py-3 font-medium">Date Range</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($pendingLeaves as $leave)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $leave->employee->user->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $leave->leaveType->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 ring-1 ring-inset ring-orange-600/20">Pending</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors" href="{{ route('admin.leave-requests.index') }}">Review &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No pending leave requests</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
    <script>
    const attendanceChartElement = document.getElementById('attendanceChart');
    if (attendanceChartElement) {
        new Chart(attendanceChartElement, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Attendance',
                data: @json($chartValues),
                backgroundColor: '{{ $uiCompanySetting->primary_color ?? '#1f4f82' }}',
                borderRadius: 6,
                maxBarThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#e2e8f0' } }
            }
        }
    });
    }
    </script>
</x-layouts.admin>
