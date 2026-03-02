<x-layouts.admin>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ isset($leaveType) ? 'Edit Leave Type' : 'Create Leave Type' }}</h2>
            <p class="text-sm text-slate-500 mt-1">Configure properties and rules for this leave category</p>
        </div>
        <a href="{{ route('admin.leave-types.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl">
        <form method="POST" action="{{ isset($leaveType) ? route('admin.leave-types.update',$leaveType) : route('admin.leave-types.store') }}">
            @csrf 
            @if(isset($leaveType)) 
                @method('PUT') 
            @endif
            
            <div class="p-6">
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('name', ($leaveType ?? null)?->name ?? '') }}" placeholder="e.g. Annual Leave" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Default Days <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="default_days" min="0" step="0.5" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-4" value="{{ old('default_days', ($leaveType ?? null)?->default_days ?? 0) }}" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">Days</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end pb-2 lg:pb-0">
                        <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 rounded-lg p-3 w-full justify-center lg:justify-start lg:mb-[2px]">
                            <input type="hidden" name="is_paid" value="0">
                            <input type="checkbox" id="is_paid" name="is_paid" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 transition-colors cursor-pointer" @checked(old('is_paid', ($leaveType ?? null)?->is_paid ?? true))>
                            <label for="is_paid" class="text-sm font-medium text-slate-700 mb-0 cursor-pointer select-none">Paid Leave</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 rounded-b-xl">
                <a href="{{ route('admin.leave-types.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Leave Type
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
