<x-layouts.admin>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Attendance Management</h2>
    </div>

    <!-- Summary Widgets -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $summary['total'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Scans</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-orange-500 mb-1">{{ $summary['late'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Late</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-red-500 mb-1">{{ $summary['rejected'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rejected Scans</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center flex flex-col justify-center items-center">
            <div class="text-3xl font-bold text-teal-500 mb-1">{{ $summary['overtime'] }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">With Overtime</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Branch</label>
                <select name="branch_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(request('branch_id')==$branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Employee</label>
                <select name="employee_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(request('employee_id')==$employee->id)>
                            {{ $employee->employee_id }} - {{ $employee->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 h-[38px]">
                <button class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">Filter</button>
                <a href="{{ route('admin.attendance.index') }}" class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors text-center flex items-center justify-center focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">Reset</a>
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200 mb-0">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ request()->fullUrlWithQuery(['tab'=>'all','page'=>1]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab==='all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">All Sessions</a>
            
            <a href="{{ request()->fullUrlWithQuery(['tab'=>'late','page'=>1]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center {{ $activeTab==='late' ? 'border-orange-500 text-orange-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                Late 
                @if($summary['late']>0)<span class="ml-2 bg-orange-100 text-orange-800 py-0.5 px-2.5 inset-y-0 rounded-full text-xs font-semibold">{{ $summary['late'] }}</span>@endif
            </a>
            
            <a href="{{ request()->fullUrlWithQuery(['tab'=>'rejected','page'=>1]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center {{ $activeTab==='rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                Rejected 
                @if($summary['rejected']>0)<span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2.5 inset-y-0 rounded-full text-xs font-semibold">{{ $summary['rejected'] }}</span>@endif
            </a>
        </nav>
    </div>

    @if($activeTab !== 'rejected')
    @php $detailMap = []; @endphp
    <div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="py-3 px-4">Employee</th>
                        <th class="py-3 px-4">Branch</th>
                        <th class="py-3 px-4">Scan</th>
                        <th class="py-3 px-4">Date/Time</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Distance</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($attendanceLogs as $log)
                    @php
                        $schedule = $scheduleMap[$log->branch_id] ?? null;
                        $scanAt = \Carbon\Carbon::parse($log->scanned_at);
                        $startAt = $schedule?->morning_in ? \Carbon\Carbon::parse($selectedDate . ' ' . $schedule->morning_in) : null;
                        $endAt = $schedule?->evening_out ? \Carbon\Carbon::parse($selectedDate . ' ' . $schedule->evening_out) : null;
                        $isLate = $startAt && $endAt && $scanAt->gt($startAt) && $scanAt->lt($endAt);
                        $scanLabel = in_array($log->scan_type, ['morning_in', 'lunch_in'], true)
                            ? 'Check-In'
                            : (in_array($log->scan_type, ['lunch_out', 'evening_out'], true) ? 'Check-Out' : '-');

                        if ($activeTab === 'late' && !$isLate) {
                            continue;
                        }

                        $statusText = $isLate ? '🔴 Late' : '🔵 Good';
                        $locationLink = ($log->latitude !== null && $log->longitude !== null)
                            ? 'https://maps.google.com/?q=' . number_format((float) $log->latitude, 6, '.', '') . ',' . number_format((float) $log->longitude, 6, '.', '')
                            : null;

                        $detailMap[$log->id]=[
                            'employee'=>$log->employee->user->name,
                            'emp_id'=>$log->employee->employee_id,
                            'department'=>$log->employee->department?->name ?? 'N/A',
                            'position'=>$log->employee->position ?? 'N/A',
                            'branch'=>$log->employee->branch?->name??'-',
                            'status'=>$statusText,
                            'date'=>$scanAt->toDateString(),
                            'datetime'=>$scanAt->format('Y-m-d H:i:s'),
                            'late'=>$isLate ? 1 : 0,
                            'early'=>0,
                            'hours'=>0,
                            'overtime'=>0,
                            'gps'=>$log->has_fake_gps?'Flagged':'Verified',
                            'distance'=>$log->distance_from_branch?round($log->distance_from_branch).'m':'-',
                            'location'=>$locationLink,
                            'scans'=>['Scan'=>$scanLabel,'Scan Time'=>$scanAt->format('H:i:s')],
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $log->employee->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $log->employee->employee_id }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $log->employee->branch?->name??'-' }}</td>
                        <td class="py-3 px-4 text-sm text-slate-700">{{ $scanLabel }}</td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $scanAt->format('Y-m-d H:i:s') }}</td>
                        <td class="py-3 px-4">
                            @if($isLate)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">🔴 Late</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">🔵 Good</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $log->distance_from_branch ? round($log->distance_from_branch) . ' m' : '—' }}</td>
                        <td class="py-3 px-4 text-right">
                            <button type="button" 
                                x-data="" 
                                x-on:click="$dispatch('open-modal', 'detailModal'); $dispatch('load-detail', '{{ $log->id }}')" 
                                class="inline-flex items-center justify-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-slate-500">No attendance data for selected filters.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($attendanceLogs, 'links'))
        <div class="p-4 border-t border-slate-200">
            {{ $attendanceLogs->links() }}
        </div>
        @endif
    </div>
    @endif

    @if($activeTab === 'rejected')
    <div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="py-3 px-4">Employee</th>
                        <th class="py-3 px-4">Branch</th>
                        <th class="py-3 px-4">Scan Type</th>
                        <th class="py-3 px-4">Time</th>
                        <th class="py-3 px-4">Distance</th>
                        <th class="py-3 px-4">Reason</th>
                        <th class="py-3 px-4">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($rejectedLogs as $rejection)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $rejection->employee->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $rejection->employee->employee_id }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $rejection->branch?->name??'-' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                {{ ucwords(str_replace('_',' ',$rejection->scan_type??'-')) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $rejection->created_at->format('H:i:s') }}</td>
                        <td class="py-3 px-4 text-sm">
                            @if($rejection->distance_from_branch)
                                <span class="text-red-500">{{ round($rejection->distance_from_branch) }} m</span>
                            @else 
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-red-600">{{ $rejection->rejection_reason }}</td>
                        <td class="py-3 px-4 text-xs text-slate-400 font-mono">{{ $rejection->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-slate-500">No rejected scans for this date.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($rejectedLogs, 'links'))
        <div class="p-4 border-t border-slate-200">
            {{ $rejectedLogs->links() }}
        </div>
        @endif
    </div>
    @endif

    <!-- Detail Modal -->
    <div x-data="{ 
            open: false,
            details: @js($detailMap ?? []),
            d: null
         }" 
         x-on:open-modal.window="if ($event.detail === 'detailModal') open = true"
         x-on:load-detail.window="d = details[$event.detail]"
         x-show="open" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" 
                 aria-hidden="true" 
                 x-on:click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 z-[110]">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-lg leading-6 font-semibold text-slate-800" id="modal-title">Attendance Detail</h3>
                        <button type="button" x-on:click="open = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                    
                    <div x-show="d" class="space-y-3 text-sm text-slate-600">
                        <p><strong class="text-slate-800">Name :</strong> <span x-text="d ? d.employee : ''"></span></p>
                        <p><strong class="text-slate-800">Status :</strong> <span x-text="d ? d.status : ''"></span></p>
                        <p class="text-slate-300">-------------------------------------</p>
                        <p><strong class="text-slate-800">ID :</strong> <span x-text="d ? d.emp_id : ''"></span></p>
                        <p><strong class="text-slate-800">Department :</strong> <span x-text="d ? d.department : ''"></span></p>
                        <p><strong class="text-slate-800">Position :</strong> <span x-text="d ? d.position : ''"></span></p>
                        <p><strong class="text-slate-800">Date/Time :</strong> <span x-text="d ? d.datetime : ''"></span></p>
                        <p><strong class="text-slate-800">Area :</strong> <span x-text="d ? d.branch : ''"></span></p>
                        <p><strong class="text-slate-800">Distance :</strong> <span x-text="d ? d.distance : ''"></span></p>
                        <p>
                            <strong class="text-slate-800">Location :</strong>
                            <template x-if="d && d.location">
                                <a :href="d.location" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Open Map</a>
                            </template>
                            <template x-if="!d || !d.location">
                                <span>N/A</span>
                            </template>
                        </p>

                        <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                             <p><strong class="text-slate-800">Late:</strong> <span x-text="d ? d.late : ''"></span> min</p>
                             <p><strong class="text-slate-800">Early Leave:</strong> <span x-text="d ? d.early : ''"></span> min</p>
                             <p><strong class="text-slate-800">Work:</strong> <span x-text="d ? d.hours : ''"></span> hrs</p>
                             <p><strong class="text-slate-800">OT:</strong> <span x-text="d ? d.overtime : ''"></span> hrs</p>
                        </div>
                        <p class="pt-2 border-t border-slate-100"><strong class="text-slate-800">GPS Status:</strong> <span x-text="d ? d.gps : ''" :class="d && d.gps === 'Flagged' ? 'text-red-500 font-medium' : ''"></span> &nbsp;|&nbsp; <strong class="text-slate-800">Distance:</strong> <span x-text="d ? d.distance : ''"></span></p>
                        
                        <div class="mt-4 border rounded-lg border-slate-200 overflow-hidden">
                            <ul class="divide-y divide-slate-100 bg-slate-50">
                                <template x-if="d && d.scans">
                                    <template x-for="(value, key) in d.scans" :key="key">
                                        <li class="px-4 py-2 flex justify-between">
                                            <span class="text-slate-500" x-text="key"></span>
                                            <strong class="text-slate-800" x-text="value"></strong>
                                        </li>
                                    </template>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                    <button type="button" x-on:click="open = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>