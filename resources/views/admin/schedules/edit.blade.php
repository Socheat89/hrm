<x-layouts.admin>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Attendance Time Setting — {{ $dayNames[$schedule->day_of_week] ?? '' }}</h2>
            <p class="text-sm text-slate-500 mt-1">Modify check-in/out time and choose 2 scans or 4 scans</p>
        </div>
        <a href="{{ route('admin.schedules.index', ['branch_id' => $schedule->branch_id]) }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Branch</label>
                    <select name="branch_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id', $schedule->branch_id) == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Day of Week</label>
                    <select name="day_of_week" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="-1" @selected(old('day_of_week') == '-1')>All Days</option>
                        @foreach($dayNames as $num => $name)
                            <option value="{{ $num }}" @selected(old('day_of_week', $schedule->day_of_week) == $num)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6 pb-6 border-b border-slate-100">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-4">Time Settings</h3>

                @php
                    $defaultScanTimes = ($schedule->lunch_out && $schedule->lunch_in) ? '4' : '2';
                @endphp

                <div class="mb-4 max-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Scan Times</label>
                    <select id="scanTimes" name="scan_times" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('scan_times') border-red-500 @enderror" required>
                        <option value="2" @selected(old('scan_times', $defaultScanTimes) == '2')>2 Scans (Morning In, Evening Out)</option>
                        <option value="4" @selected(old('scan_times', $defaultScanTimes) == '4')>4 Scans (Morning/Lunch/Evening)</option>
                    </select>
                    @error('scan_times')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Morning In</label>
                        <input type="time" name="morning_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('morning_in', substr($schedule->morning_in??'',0,5)) }}">
                    </div>
                    <div id="lunchOutWrap">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lunch Out</label>
                        <input type="time" id="lunchOut" name="lunch_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('lunch_out', substr($schedule->lunch_out??'',0,5)) }}">
                    </div>
                    <div id="lunchInWrap">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lunch In</label>
                        <input type="time" id="lunchIn" name="lunch_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('lunch_in', substr($schedule->lunch_in??'',0,5)) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Evening Out</label>
                        <input type="time" name="evening_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('evening_out', substr($schedule->evening_out??'',0,5)) }}">
                    </div>
                </div>
            </div>

            <input type="hidden" name="late_grace_minutes" value="0">
            <input type="hidden" name="early_leave_grace_minutes" value="0">

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.schedules.index', ['branch_id' => $schedule->branch_id]) }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">Update Schedule</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const scanTimes = document.getElementById('scanTimes');
            const lunchOutWrap = document.getElementById('lunchOutWrap');
            const lunchInWrap = document.getElementById('lunchInWrap');
            const lunchOut = document.getElementById('lunchOut');
            const lunchIn = document.getElementById('lunchIn');

            function toggleLunchFields() {
                const isFourScans = scanTimes.value === '4';
                lunchOutWrap.style.display = isFourScans ? '' : 'none';
                lunchInWrap.style.display = isFourScans ? '' : 'none';

                if (isFourScans) {
                    lunchOut.setAttribute('required', 'required');
                    lunchIn.setAttribute('required', 'required');
                } else {
                    lunchOut.removeAttribute('required');
                    lunchIn.removeAttribute('required');
                    lunchOut.value = '';
                    lunchIn.value = '';
                }
            }

            scanTimes.addEventListener('change', toggleLunchFields);
            toggleLunchFields();
        })();
    </script>
</x-layouts.admin>