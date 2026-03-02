<x-layouts.admin>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h2>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <small class="text-slate-500 font-medium uppercase tracking-wider text-xs">Total Employees</small>
            <h4 class="text-3xl font-bold text-slate-800 mt-2">{{ $totalEmployees }}</h4>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <small class="text-slate-500 font-medium uppercase tracking-wider text-xs">Present Today</small>
            <h4 class="text-3xl font-bold text-green-600 mt-2">{{ $todayAttendance }}</h4>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <small class="text-slate-500 font-medium uppercase tracking-wider text-xs">Late Today</small>
            <h4 class="text-3xl font-bold text-orange-500 mt-2">{{ $lateEmployeesCount }}</h4>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <small class="text-slate-500 font-medium uppercase tracking-wider text-xs">Leave Today</small>
            <h4 class="text-3xl font-bold text-blue-500 mt-2">{{ $onLeaveToday }}</h4>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 col-span-2 md:col-span-4 xl:col-span-1 border-l-4 border-l-blue-600">
            <small class="text-slate-500 font-medium uppercase tracking-wider text-xs">Monthly Payroll</small>
            <h4 class="text-3xl font-bold text-slate-800 mt-2">${{ number_format($monthlyPayrollCost,2) }}</h4>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 h-full">
                <div class="flex justify-between items-center mb-4">
                    <h6 class="font-bold text-lg text-slate-800">Monthly Attendance</h6>
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
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-full flex flex-col">
                <div class="p-5 border-b border-slate-100">
                    <h6 class="font-bold text-lg text-slate-800">Late Employees Today</h6>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
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
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h6 class="font-bold text-lg text-slate-800">Pending Leave Requests</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
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
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    }
    </script>
</x-layouts.admin>
