<x-layouts.admin>
    <div x-data="employeeSchedule()">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Schedule: {{ $employee->user->name }}</h2>
                <p class="text-sm text-slate-500 mt-1">Manage working hours for this employee. Overrides branch defaults.</p>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
    
        @if(session('status'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-start justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium">{{ session('status') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Edit Form -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 sticky top-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-4">Set Schedule</h3>
                    <form method="POST" action="{{ route('admin.employees.schedule.store', $employee) }}">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Day of Week</label>
                            <select name="day_of_week" x-model.number="form.day_of_week" @change="loadDay($event.target.value)" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <template x-for="(label, value) in dayNames" :key="value">
                                    <option :value="value" x-text="label"></option>
                                </template>
                                <option disabled>──────────</option>
                                <option value="-1">Apply to All Days</option>
                            </select>
                        </div>
    
                        <div class="space-y-4">
                            <!-- Morning -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Morning In</label>
                                <input type="time" name="morning_in" x-model="form.morning_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <!-- Lunch Toggle -->
                            <div class="flex items-center justify-between py-1">
                                <label class="text-sm font-medium text-slate-700">Lunch Break?</label>
                                <button type="button" 
                                    @click="form.has_lunch = !form.has_lunch; toggleLunch()" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                                    :class="form.has_lunch ? 'bg-blue-600' : 'bg-slate-200'">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true" 
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" 
                                        :class="form.has_lunch ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                                <input type="hidden" name="scan_times" :value="form.has_lunch ? 4 : 2">
                            </div>

                            <!-- Lunch Times -->
                            <div class="grid grid-cols-2 gap-4" x-show="form.has_lunch" x-transition>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Lunch Out</label>
                                    <input type="time" name="lunch_out" x-model="form.lunch_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Lunch In</label>
                                    <input type="time" name="lunch_in" x-model="form.lunch_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                            </div>

                            <!-- Evening -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Evening Out</label>
                                <input type="time" name="evening_out" x-model="form.evening_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                             <!-- Grace Periods -->
                             <div class="border-t border-slate-100 pt-4 mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Late Grace (Min)</label>
                                    <input type="number" name="late_grace_minutes" x-model="form.late_grace_minutes" min="0" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Early Leave (Min)</label>
                                    <input type="number" name="early_leave_grace_minutes" x-model="form.early_leave_grace_minutes" min="0" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
    
                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex justify-center items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Save Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    
            <!-- Schedule List -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Day</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Hours</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Lunch</th>
                                <th class="px-6 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Grace</th>
                                <th class="px-6 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Source</th>
                                <th class="px-6 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($dayNames as $dayNum => $dayName)
                                @php
                                    $custom = $schedules->get($dayNum);
                                    $branchDefault = $branchSchedules->get($dayNum);
                                    $active = $custom ?? $branchDefault;
                                    $hasLunch = $active && ($active->lunch_out || $active->lunch_in);
                                @endphp
                                <tr class="hover:bg-slate-50/50 cursor-pointer transition-colors" @click="loadDay({{ $dayNum }})">
                                    <td class="px-6 py-3 font-medium text-slate-800">
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 rounded-full mr-2 {{ $active ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                            {{ $dayName }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-slate-600">
                                        @if($active)
                                            <div>In: <span class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($active->morning_in)->format('h:i A') }}</span></div>
                                            <div>Out: <span class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($active->evening_out)->format('h:i A') }}</span></div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-slate-600">
                                        @if($hasLunch)
                                            <div class="text-xs">
                                                Out: {{ \Carbon\Carbon::parse($active->lunch_out)->format('h:i A') }}<br>
                                                In: {{ \Carbon\Carbon::parse($active->lunch_in)->format('h:i A') }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs italic">No Break</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right text-slate-500 text-xs">
                                        @if($active)
                                            <div>L: {{ $active->late_grace_minutes ?? 0 }}m</div>
                                            <div>E: {{ $active->early_leave_grace_minutes ?? 0 }}m</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        @if($custom)
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Custom</span>
                                        @elseif($branchDefault)
                                            <span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">Default</span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">No Schedule</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right" @click.stop>
                                        @if($custom)
                                            <form method="POST" action="{{ route('admin.employees.schedule.destroy', ['employee' => $employee, 'schedule' => $custom]) }}" class="inline-block" onsubmit="return confirm('Remove custom schedule? It will revert to branch default.');">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 text-xs font-medium hover:underline p-1">Reset</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100 flex items-start gap-3">
                     <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                     <div>
                         <h4 class="text-sm font-semibold text-blue-800">How Schedules Work</h4>
                         <p class="text-xs text-blue-600 mt-1 space-y-1">
                            <span>1. <b>Custom Schedule</b>: Takes priority. Set specific hours for this employee.</span><br>
                            <span>2. <b>Branch Default</b>: Used if no custom schedule is set.</span><br>
                            <span>3. <b>Click on a row</b> in the table to load its settings into the form.</span>
                         </p>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function employeeSchedule() {
            return {
                employeeId: {{ $employee->id }},
                schedules: @json($schedules->keyBy('day_of_week')),
                branchSchedules: @json($branchSchedules->keyBy('day_of_week')),
                dayNames: @json($dayNames),
                form: {
                    day_of_week: -1,
                    morning_in: '{{ old('morning_in') }}',
                    lunch_out: '{{ old('lunch_out') }}',
                    lunch_in: '{{ old('lunch_in') }}',
                    evening_out: '{{ old('evening_out') }}',
                    late_grace_minutes: '{{ old('late_grace_minutes', $employee->branch->late_grace_minutes ?? 0) }}',
                    early_leave_grace_minutes: '{{ old('early_leave_grace_minutes', $employee->branch->early_leave_grace_minutes ?? 0) }}',
                    has_lunch: true
                },
                init() {
                    let today = new Date().getDay();
                    this.loadDay(today);
                },
                loadDay(day) {
                    this.form.day_of_week = day;
                    
                    if (day == -1) {
                        // 'Apply to All Days' mode: clear or set defaults
                        this.resetForm();
                        return;
                    }

                    // Find existing schedule logic: Custom > Branch > Empty
                    let custom = this.schedules[day];
                    let branch = this.branchSchedules[day];
                    let record = custom || branch;

                    if (record) {
                        this.form.morning_in = this.formatTime(record.morning_in);
                        this.form.lunch_out = this.formatTime(record.lunch_out);
                        this.form.lunch_in = this.formatTime(record.lunch_in);
                        this.form.evening_out = this.formatTime(record.evening_out);
                        this.form.late_grace_minutes = record.late_grace_minutes ?? 0;
                        this.form.early_leave_grace_minutes = record.early_leave_grace_minutes ?? 0;
                        
                        // Determine lunch status
                        this.form.has_lunch = !!(record.lunch_out || record.lunch_in);
                    } else {
                        this.resetForm();
                    }
                },
                resetForm() {
                    this.form.morning_in = '';
                    this.form.lunch_out = '';
                    this.form.lunch_in = '';
                    this.form.evening_out = '';
                    this.form.late_grace_minutes = 0;
                    this.form.early_leave_grace_minutes = 0;
                    this.form.has_lunch = true;
                },
                formatTime(timeStr) {
                    if (!timeStr) return '';
                    return timeStr.length > 5 ? timeStr.substring(0, 5) : timeStr;
                },
                toggleLunch() {
                    if (!this.form.has_lunch) {
                        this.form.lunch_out = '';
                        this.form.lunch_in = '';
                    }
                }
            }
        }
    </script>
</x-layouts.admin>
