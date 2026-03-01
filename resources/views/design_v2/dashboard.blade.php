@php($title = 'Dashboard')
@extends('design_v2.layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="text-sm text-slate-500">Employees</div>
            <div class="text-2xl font-semibold mt-2">{{ \\App\\Models\\Employee::count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="text-sm text-slate-500">Open Leave Requests</div>
            <div class="text-2xl font-semibold mt-2">{{ \\App\\Models\\LeaveRequest::where('status','pending')->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="text-sm text-slate-500">This Month Payrolls</div>
            <div class="text-2xl font-semibold mt-2">{{ \\App\\Models\\Payroll::whereYear('created_at', now()->year)->count() }}</div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Recent Employees</h3>
            <ul class="space-y-2">
                @foreach(\\App\\Models\\Employee::latest()->limit(6)->get() as $emp)
                    <li class="flex items-center justify-between">
                        <div>
                            <div class="font-medium">{{ $emp->full_name ?? $emp->name ?? '—' }}</div>
                            <div class="text-xs text-slate-500">{{ $emp->email ?? 'no-email' }}</div>
                        </div>
                        <div class="text-sm text-slate-400">{{ $emp->branch->name ?? '' }}</div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Quick Actions</h3>
            <div class="flex flex-col gap-2">
                <a class="px-3 py-2 bg-sky-600 text-white rounded" href="#">Create Employee</a>
                <a class="px-3 py-2 border rounded" href="#">Generate Payroll</a>
                <a class="px-3 py-2 border rounded" href="#">Export Attendance</a>
            </div>
        </div>
    </div>
@endsection
