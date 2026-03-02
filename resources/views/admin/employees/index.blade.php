<x-layouts.admin>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        
        <!-- Page Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-800 to-slate-600 tracking-tight">
                    Employees
                </h1>
                <p class="text-slate-500 text-sm mt-2 font-medium">Manage and view your team members.</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-3">
                <!-- Add Employee Button -->
                <a href="{{ route('admin.employees.create') }}" class="group relative inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white transition-all duration-200 bg-slate-800 border border-transparent rounded-lg shadow-md hover:bg-slate-900 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 transition-transform duration-200 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Employee
                </a>
            </div>
        </div>

        @if(session('status'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="mb-6 bg-emerald-50/80 backdrop-blur-sm border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-start justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100/50 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Success</h3>
                        <p class="text-sm opacity-90 text-emerald-700">{{ session('status') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 hover:bg-emerald-100/50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-md border border-slate-200/60 rounded-2xl shadow-sm mb-6 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter Options
            </h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Branch</label>
                    <div class="relative group">
                        <select name="branch_id" class="w-full pl-3 pr-10 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none transition-all cursor-pointer hover:bg-white hover:border-slate-300">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(request('branch_id')==$branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 group-hover:text-indigo-500 transition-colors">
                             <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Department</label>
                    <div class="relative group">
                         <select name="department_id" class="w-full pl-3 pr-10 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none transition-all cursor-pointer hover:bg-white hover:border-slate-300">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 group-hover:text-indigo-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Status</label>
                    <div class="relative group">
                        <select name="employment_status" class="w-full pl-3 pr-10 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none transition-all cursor-pointer hover:bg-white hover:border-slate-300">
                            <option value="">All Status</option>
                            <option value="active" @selected(request('employment_status')==='active')>Active</option>
                            <option value="suspended" @selected(request('employment_status')==='suspended')>Suspended</option>
                            <option value="resigned" @selected(request('employment_status')==='resigned')>Resigned</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 group-hover:text-indigo-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl px-4 py-2.5 transition-all shadow-md hover:shadow-lg focus:ring-2 focus:ring-slate-500 focus:ring-offset-1 flex justify-center items-center gap-2 transform active:scale-95">
                        <svg class="w-4 h-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="flex-none bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-slate-800 text-sm font-medium rounded-xl px-4 py-2.5 transition-colors shadow-sm text-center flex items-center justify-center tooltip" title="Reset Filters">
                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest pl-6">Employee</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest hidden sm:table-cell">Details</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest pr-6">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-indigo-50/40 transition-colors duration-200 group">
                                <!-- Employee Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="relative h-12 w-12 shrink-0">
                                            @if($employee->user->photo_path)
                                                <img class="h-12 w-12 rounded-full object-cover ring-4 ring-white shadow-sm" src="{{ route('users.photo', $employee->user) }}" alt="{{ $employee->user->name }}" />
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md ring-4 ring-white">
                                                    {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <!-- Status Dot on Avatar -->
                                            <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white {{ $employee->employment_status === 'active' ? 'bg-emerald-400' : ($employee->employment_status === 'suspended' ? 'bg-amber-400' : 'bg-slate-400') }}"></span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $employee->user->name }}</div>
                                            <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                                ID: {{ $employee->employee_id }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Mobile optimized details visible only on small screens -->
                                    <div class="sm:hidden mt-2 ml-16 space-y-1">
                                        <div class="text-xs text-slate-500">{{ $employee->department?->name ?? 'Unassigned' }} &bull; {{ $employee->branch?->name }}</div>
                                        <div class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium {{ $employee->employment_status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ ucfirst($employee->employment_status) }}
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Details Column (Dept/Branch) -->
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-slate-700">{{ $employee->department?->name ?? 'Unassigned' }}</div>
                                        <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            {{ $employee->branch?->name }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    @php
                                        $statusClasses = match($employee->employment_status) {
                                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100 ring-emerald-600/20',
                                            'suspended' => 'bg-amber-50 text-amber-700 border-amber-100 ring-amber-600/20',
                                            'resigned' => 'bg-rose-50 text-rose-700 border-rose-100 ring-rose-600/20',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100 ring-slate-500/20',
                                        };
                                        $dotClass = match($employee->employment_status) {
                                            'active' => 'bg-emerald-500',
                                            'suspended' => 'bg-amber-500',
                                            'resigned' => 'bg-rose-500',
                                            default => 'bg-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border ring-1 ring-inset {{ $statusClasses }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ ucfirst($employee->employment_status) }}
                                    </span>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <!-- Schedule Button -->
                                        <a href="{{ route('admin.employees.schedule.index', $employee) }}" class="relative p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200 tooltip-trigger group/btn" title="Shift Schedule">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.employees.edit', $employee) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="Edit Profile">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline-block" data-confirm="true">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200" title="Delete Account" onclick="return confirm('Are you sure you want to delete this employee?')">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 ring-8 ring-slate-50/50">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        </div>
                                        <p class="text-slate-800 font-semibold text-lg">No employees found</p>
                                        <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">None of your team members match the current filters. Try resetting them or add a new employee.</p>
                                        <a href="{{ route('admin.employees.create') }}" class="mt-4 text-indigo-600 font-medium hover:text-indigo-700 text-sm">
                                            + Add New Employee
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($employees->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
