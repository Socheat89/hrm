<x-layouts.admin>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Add Schedule</h2>
            <p class="text-sm text-slate-500 mt-1">Set working hours and grace periods</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Branch</label>
                    <select name="branch_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('branch_id') border-red-500 @enderror" required>
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id', $selected['branch_id'] ?? '') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Day of Week</label>
                    <select name="day_of_week" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('day_of_week') border-red-500 @enderror" required>
                        <option value="">Select Day</option>
                        @foreach($dayNames as $num => $name)
                            <option value="{{ $num }}" @selected(old('day_of_week', $selected['day_of_week'] ?? '') == $num)>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-6 pb-6 border-b border-slate-100">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-4">Time Settings</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Morning In</label>
                        <input type="time" name="morning_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('morning_in', '08:00') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lunch Out</label>
                        <input type="time" name="lunch_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('lunch_out', '12:00') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lunch In</label>
                        <input type="time" name="lunch_in" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('lunch_in', '13:00') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Evening Out</label>
                        <input type="time" name="evening_out" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('evening_out', '17:00') }}">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-4">Grace Periods</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Late Grace</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" name="late_grace_minutes" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pr-16" value="{{ old('late_grace_minutes', 10) }}" min="0" max="120">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm">minutes</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Allow up to N minutes after Morning In before marking Late.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Early Leave Grace</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" name="early_leave_grace_minutes" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pr-16" value="{{ old('early_leave_grace_minutes', 10) }}" min="0" max="120">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm">minutes</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Allow leaving N minutes before Evening Out without marking Early Leave.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.schedules.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">Save Schedule</button>
            </div>
        </form>
    </div>
</x-layouts.admin>