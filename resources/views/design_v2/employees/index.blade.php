@php($title = 'Employees')
@extends('design_v2.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Employees</h2>
        <a href="#" class="px-3 py-1 bg-sky-600 text-white rounded">New Employee</a>
    </div>

    @php($employees = \\App\\Models\\Employee::latest()->limit(25)->get())

    <div class="bg-white shadow-sm rounded overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Branch</th>
                <th class="px-4 py-3">Joined</th>
            </tr>
            </thead>
            <tbody>
            @forelse($employees as $emp)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $emp->full_name ?? $emp->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $emp->email }}</td>
                    <td class="px-4 py-3">{{ $emp->department->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $emp->branch->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-slate-500">{{ optional($emp->created_at)->toDateString() }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No employees found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
