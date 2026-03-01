<x-layouts.admin>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Employees Management</h2>
        <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Employee
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


    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                <select name="department_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">

                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="employment_status" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm py-2">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('employment_status')==='active')>Active</option>
                    <option value="suspended" @selected(request('employment_status')==='suspended')>Suspended</option>
                    <option value="resigned" @selected(request('employment_status')==='resigned')>Resigned</option>
                </select>
            </div>
            <div class="flex gap-3 h-[38px]">
                <button class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">Filter</button>
                <a href="{{ route('admin.employees.index') }}" class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors text-center flex items-center justify-center focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Photo</th>
                        <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Name & ID</th>
                        <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Department</th>
                        <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Branch</th>
                        <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-600 uppercase tracking-wider text-xs">Status</th>
                        <th scope="col" class="px-6 py-3 text-right font-semibold text-slate-600 uppercase tracking-wider text-xs">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if($employee->user->photo_path)
                                    <img src="{{ asset('storage/'.$employee->user->photo_path) }}" class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm" alt="Photo">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shadow-sm border border-blue-200">{{ strtoupper(substr($employee->user->name,0,1)) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ $employee->user->name }}</div>
                                <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $employee->employee_id }}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-slate-600">{{ $employee->department?->name ?? '-' }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-slate-600">{{ $employee->branch?->name }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @php
                                    $statusClasses = match($employee->employment_status) {
                                        'active' => 'bg-green-100 text-green-700 ring-green-600/20',
                                        'suspended' => 'bg-orange-100 text-orange-700 ring-orange-600/20',
                                        default => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusClasses }}">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="inline-flex text-blue-600 hover:text-blue-800 mr-3 px-2 py-1 hover:bg-blue-50 rounded transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline-block" data-confirm="true">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex text-red-600 hover:text-red-800 px-2 py-1 hover:bg-red-50 rounded transition-colors">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $employees->links() }}
        </div>
    </div>
</x-layouts.admin>
