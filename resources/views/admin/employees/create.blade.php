<x-layouts.admin>
    @php
        $editing = isset($employee);
        $selectedRole = old('role', ($employee ?? null)?->user?->roles?->first()?->name ?? 'Employee');
    @endphp
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $editing ? 'Edit Employee' : 'Create Employee' }}</h2>
            <p class="text-sm text-slate-500 mt-1">Fill in the information below to {{ $editing ? 'update the' : 'add a new' }} employee.</p>
        </div>
        <a href="{{ route('admin.employees.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-sm font-bold">There were {{ $errors->count() }} errors with your submission</h3>
            </div>
            <ul class="list-disc list-inside text-sm ml-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-5xl">
        <form method="POST" enctype="multipart/form-data" action="{{ $editing ? route('admin.employees.update', $employee) : route('admin.employees.store') }}">
            @csrf 
            @if($editing) 
                @method('PUT') 
            @endif
            
            <div class="p-8">
                <!-- User Account Info -->
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="name" value="{{ old('name', ($employee ?? null)?->user?->name ?? '') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="email" value="{{ old('email', ($employee ?? null)?->user?->email ?? '') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                        <input type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="phone" value="{{ old('phone', ($employee ?? null)?->user?->phone ?? '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password {!! $editing ? '<span class="text-slate-400 font-normal">(Leave blank to keep current)</span>' : '<span class="text-red-500">*</span>' !!}</label>
                        <input type="password" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="password" {{ $editing ? '' : 'required' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="role" required>
                            <option value="Admin / HR" @selected($selectedRole === 'Admin / HR')>Admin / HR</option>
                            <option value="Employee" @selected($selectedRole === 'Employee')>Employee</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Profile Photo</label>
                        <input type="file" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100" name="photo" accept="image/*">
                    </div>
                </div>

                <!-- Employment Details -->
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Branch <span class="text-red-500">*</span></label>
                        <select class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="branch_id" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id', ($employee ?? null)?->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                        <select class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="department_id">
                            <option value="">N/A</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', ($employee ?? null)?->department_id ?? '') == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Position <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="position" value="{{ old('position', ($employee ?? null)?->position ?? '') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Salary Type <span class="text-red-500">*</span></label>
                        <select class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="salary_type" required>
                            <option value="monthly" @selected(old('salary_type', ($employee ?? null)?->salary_type ?? '') === 'monthly')>Monthly</option>
                            <option value="daily" @selected(old('salary_type', ($employee ?? null)?->salary_type ?? '') === 'daily')>Daily</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Base Salary <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-7" name="base_salary" value="{{ old('base_salary', ($employee ?? null)?->base_salary ?? '') }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Overtime Rate / Hour (Optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-7" name="ot_rate_per_hour" value="{{ old('ot_rate_per_hour', ($employee ?? null)?->ot_rate_per_hour ?? '') }}" placeholder="Default: Company Spec">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Leave Deduction / Day (Optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-7" name="leave_deduction_per_day" value="{{ old('leave_deduction_per_day', ($employee ?? null)?->leave_deduction_per_day ?? '') }}" placeholder="Default: Salary / 30">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="employment_status" required>
                            <option value="active" @selected(old('employment_status', ($employee ?? null)?->employment_status ?? 'active') === 'active')>Active</option>
                            <option value="suspended" @selected(old('employment_status', ($employee ?? null)?->employment_status ?? '') === 'suspended')>Suspended</option>
                            <option value="resigned" @selected(old('employment_status', ($employee ?? null)?->employment_status ?? '') === 'resigned')>Resigned</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Join Date <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="join_date" value="{{ old('join_date', ($employee ?? null)?->join_date?->toDateString() ?? '') }}" required>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex items-center justify-end gap-3 rounded-b-xl">
                <a href="{{ route('admin.employees.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Employee
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
