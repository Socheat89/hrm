<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Employees -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-blue-50 text-blue-600 mb-4 inline-flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-slate-500 font-medium text-sm tracking-wide uppercase">Total Employees</h3>
                    <p class="text-3xl font-bold text-slate-800 mt-1">142</p>
                </div>
            </div>

            <!-- Present Today -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-green-50 text-green-600 mb-4 inline-flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-slate-500 font-medium text-sm tracking-wide uppercase">Present Today</h3>
                    <p class="text-3xl font-bold text-slate-800 mt-1">128</p>
                    <p class="text-sm font-medium text-green-600 mt-2">+2% from yesterday</p>
                </div>
            </div>

            <!-- On Leave -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-orange-50 text-orange-600 mb-4 inline-flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-slate-500 font-medium text-sm tracking-wide uppercase">On Leave</h3>
                    <p class="text-3xl font-bold text-slate-800 mt-1">12</p>
                    <p class="text-sm font-medium text-orange-600 mt-2">5 approvals pending</p>
                </div>
            </div>

            <!-- Payroll Due -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-purple-50 text-purple-600 mb-4 inline-flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-slate-500 font-medium text-sm tracking-wide uppercase">Next Payroll</h3>
                    <p class="text-3xl font-bold text-slate-800 mt-1">In 4 Days</p>
                    <p class="text-sm font-medium text-slate-500 mt-2">Est. $45,200.00</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity / Attendance -->
            <div class="bg-white lg:col-span-2 rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                    <h3 class="font-bold text-lg text-slate-800">Recent Attendance Logs</h3>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">View All</a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Time In</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Time Out</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=0D8ABC&color=fff" alt="Avatar">
                                    <div>
                                        <p class="font-semibold text-slate-800">Sarah Johnson</p>
                                        <p class="text-xs text-slate-400">Marketing Dept</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Checked In
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">08:45 AM</td>
                                <td class="px-6 py-4 text-slate-400">--:-- --</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors bg-white">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name=Mike+Davis&background=F59E0B&color=fff" alt="Avatar">
                                    <div>
                                        <p class="font-semibold text-slate-800">Mike Davis</p>
                                        <p class="text-xs text-slate-400">Engineering</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Late In
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">09:15 AM</td>
                                <td class="px-6 py-4 text-slate-400">--:-- --</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name=Alex+Carter&background=64748B&color=fff" alt="Avatar">
                                    <div>
                                        <p class="font-semibold text-slate-800">Alex Carter</p>
                                        <p class="text-xs text-slate-400">Sales</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Checked Out
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">08:00 AM</td>
                                <td class="px-6 py-4 text-slate-700 font-medium">05:05 PM</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Approvals Widget -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col">
                <div class="px-6 py-4 flex justify-between items-center border-b border-slate-200 bg-slate-50/50 rounded-t-xl">
                    <h3 class="font-bold text-lg text-slate-800">Pending Actions</h3>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-bold ring-1 ring-inset ring-orange-600/20">
                        2
                    </span>
                </div>
                <div class="p-6 space-y-4 flex-1">
                    <!-- Item -->
                    <div class="flex items-start gap-4 p-4 bg-orange-50/30 rounded-lg border border-orange-100/50 hover:bg-orange-50/50 transition-colors group relative">
                        <div class="h-10 w-10 flex-shrink-0 bg-white shadow-sm rounded-full flex items-center justify-center border border-orange-100 text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">Annual Leave Request</p>
                            <p class="text-xs text-slate-500 mt-1">John Doe wants <span class="font-medium text-slate-700">3 Days</span></p>
                            <p class="text-xs text-slate-400 mt-0.5">Mar 10 - Mar 12</p>
                            <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity absolute top-2 right-2 flex-col">
                                <!-- Hover quick actions could go here -->
                            </div>
                            <div class="flex gap-2 mt-3">
                                <button class="text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md font-medium shadow-sm transition-colors focus:ring-2 focus:ring-green-500 focus:ring-offset-1 outline-none">Approve</button>
                                <button class="text-xs bg-white text-slate-600 border border-slate-300 hover:bg-slate-50 hover:text-slate-800 px-3 py-1.5 rounded-md font-medium shadow-sm transition-colors focus:ring-2 focus:ring-slate-300 focus:ring-offset-1 outline-none">Decline</button>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="p-4 border-t border-slate-200 mt-auto bg-slate-50 rounded-b-xl text-center text-sm">
                     <button class="font-medium text-blue-600 hover:text-blue-800 transition-colors focus:outline-none focus:underline">
                         View all pending actions &rarr;
                     </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
