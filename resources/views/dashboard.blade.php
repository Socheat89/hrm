<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Welcome back! Here's what's happening today.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-slate-400"></i>
                    Mar 07, 2026
                </button>
                <button class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add Employee
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 pb-10">
        <!-- Modern Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Employees -->
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50/50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">+12%</span>
                    </div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Total Workforce</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">1,284</h3>
                    <p class="text-slate-400 text-xs mt-auto pt-4 border-t border-slate-50">84 active this month</p>
                </div>
            </div>

            <!-- Present Today -->
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                 <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50/50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-user-check text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">94%</span>
                    </div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Present Today</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">1,120</h3>
                    <p class="text-slate-400 text-xs mt-auto pt-4 border-t border-slate-50">164 remaining</p>
                </div>
            </div>

            <!-- On Leave -->
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                 <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50/50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-umbrella-beach text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">8 Pending</span>
                    </div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Active Leaves</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">42</h3>
                    <p class="text-slate-400 text-xs mt-auto pt-4 border-t border-slate-50">Return expected today: 3</p>
                </div>
            </div>

            <!-- Payroll Total -->
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                 <div class="absolute -right-4 -top-4 w-24 h-24 bg-violet-50/50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-wallet text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-violet-600 bg-violet-50 px-2 py-1 rounded-lg">In 5 Days</span>
                    </div>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Monthly Payroll</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">$24.8k</h3>
                    <p class="text-slate-400 text-xs mt-auto pt-4 border-t border-slate-50">Est. Tax: $2.4k</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Attendance Trends Mockup -->
            <div class="bg-white lg:col-span-2 rounded-[32px] border border-slate-100 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900">Attendance Trends</h3>
                        <p class="text-slate-500 text-sm font-medium">Daily check-in volume for the last 7 days</p>
                    </div>
                    <select class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-600 focus:ring-2 focus:ring-blue-100 cursor-pointer">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                    </select>
                </div>
                
                <div class="flex items-end justify-between h-48 gap-3 mt-4">
                    @foreach([45, 62, 85, 30, 95, 70, 88] as $height)
                        <div class="flex-1 group relative">
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $height }}%
                            </div>
                            <div class="w-full bg-blue-50 rounded-t-xl group-hover:bg-blue-100 transition-colors" style="height: {{ $height }}%">
                                <div class="w-full h-full bg-blue-600/10 rounded-t-xl"></div>
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 text-center mt-3 uppercase tracking-tighter">Day {{ $loop->iteration }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Stats/Profile -->
            <div class="bg-slate-900 rounded-[32px] p-8 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/20 rounded-full blur-[60px]"></div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-[22px] border-2 border-blue-500/50 p-1">
                            <img class="w-full h-full rounded-[18px] object-cover" src="https://ui-avatars.com/api/?name=Admin+User&background=3b82f6&color=fff" alt="Avatar">
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Phnom Penh office</h4>
                            <p class="text-blue-400 text-xs font-medium uppercase tracking-widest">Main Branch</p>
                        </div>
                    </div>

                    <div class="space-y-6 mt-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">Server Status</span>
                            <span class="text-emerald-400 font-bold flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Operational
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">Data Storage</span>
                            <span class="text-slate-200 font-bold">4.2 GB / 10 GB</span>
                        </div>
                        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full w-[42%]"></div>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">Active Tenants</span>
                            <span class="text-slate-200 font-bold">128 Companies</span>
                        </div>
                    </div>

                    <button class="mt-auto w-full py-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-2xl text-sm font-bold transition-all backdrop-blur-sm">
                        Enterprise Settings &rarr;
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity Table -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-8 pb-4 flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 px-2">Live Attendance</h3>
                    <a href="#" class="text-blue-600 text-sm font-bold hover:underline transition-all">View Full Log</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                                <th class="px-8 py-5">Employee</th>
                                <th class="px-8 py-5 text-center">Status</th>
                                <th class="px-8 py-5 text-right">Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach([
                                ['Sarah Johnson', 'On Time', '08:42 AM', 'https://ui-avatars.com/api/?name=Sarah+J&background=E0F2FE&color=0369A1'],
                                ['Michael Chen', 'Late', '09:15 AM', 'https://ui-avatars.com/api/?name=Michael+C&background=FEF3C7&color=B45309'],
                                ['Emily Davis', 'On Time', '08:55 AM', 'https://ui-avatars.com/api/?name=Emily+D&background=DCFCE7&color=15803D'],
                            ] as $row)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5 flex items-center gap-4">
                                    <img src="{{ $row[3] }}" class="w-10 h-10 rounded-xl shadow-sm border border-slate-100" />
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $row[0] }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Creative Team</p>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $row[1] == 'Late' ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $row[1] }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <p class="text-sm font-bold text-slate-900">{{ $row[2] }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Check-in</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-extrabold text-slate-900">Pending Approvals</h3>
                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-[10px] font-bold flex items-center justify-center">04</span>
                </div>

                <div class="space-y-4">
                    @foreach(['Annual Leave', 'Work From Home', 'Medical Reimbursement'] as $request)
                    <div class="p-6 bg-[#fafafa] rounded-[24px] border border-slate-100 hover:border-blue-200 transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $request }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Requested by Alex Ray</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">2h ago</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex-1 py-3 bg-white border border-slate-200 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all">Approve</button>
                            <button class="px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">View</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
