<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f4c81">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>{{ config('app.name', 'HRM') }}</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css', true) }}" rel="stylesheet">
    <style>
        :root { --brand: #0f4c81; --brand-soft: #e8f0f8; }
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0f4c81, #154d76); }
        .sidebar a { color: #eaf2fa; text-decoration: none; display: block; padding: .55rem .75rem; border-radius: .45rem; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,.2); }
        .panel-card { border: 0; border-radius: .9rem; box-shadow: 0 0.25rem 1rem rgba(0,0,0,.06); }
        .scan-btn { width: 100%; font-size: 1.15rem; padding: 1rem; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 col-md-3 p-3 sidebar text-white">
            <h5 class="fw-bold">{{ config('app.name', 'HRM') }}</h5>
            <p class="small mb-3">{{ auth()->user()->name }}</p>
            @if(auth()->user()->hasAnyRole(['Super Admin','Admin / HR']))
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">Employees</a>
                <a class="{{ request()->routeIs('admin.branches.*') ? 'active' : '' }}" href="{{ route('admin.branches.index') }}">Branches</a>
                <a class="{{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">Departments</a>
                <a class="{{ request()->routeIs('admin.leave-types.*') ? 'active' : '' }}" href="{{ route('admin.leave-types.index') }}">Leave Types</a>
                <a class="{{ request()->routeIs('admin.leave-requests.*') ? 'active' : '' }}" href="{{ route('admin.leave-requests.index') }}">Leave Requests</a>
                <a class="{{ request()->routeIs('admin.payrolls.*') ? 'active' : '' }}" href="{{ route('admin.payrolls.index') }}">Payroll</a>
                <a class="{{ request()->routeIs('admin.attendance-qr.*') ? 'active' : '' }}" href="{{ route('admin.attendance-qr.index') }}">Attendance QR</a>
            @endif
            @if(auth()->user()->hasRole('Employee'))
                <a class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">Dashboard</a>
                <a class="{{ request()->routeIs('employee.attendance.scan') ? 'active' : '' }}" href="{{ route('employee.attendance.scan') }}">Scan Attendance</a>
                <a class="{{ request()->routeIs('employee.attendance.index') ? 'active' : '' }}" href="{{ route('employee.attendance.index') }}">My Attendance</a>
                <a class="{{ request()->routeIs('employee.leave.*') ? 'active' : '' }}" href="{{ route('employee.leave.index') }}">Leave Request</a>
                <a class="{{ request()->routeIs('employee.salary.*') ? 'active' : '' }}" href="{{ route('employee.salary.index') }}">My Salary</a>
            @endif
            <a class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf <button class="btn btn-sm btn-light w-100">Logout</button></form>
        </aside>
        <main class="col-lg-10 col-md-9 p-3 p-lg-4">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            {{ $slot }}
        </main>
    </div>
</div>
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js', true) }}"></script>
<script>
if ('serviceWorker' in navigator) {
    @if(app()->environment('production'))
        navigator.serviceWorker.register('/service-worker.js').catch(() => {});
    @else
        navigator.serviceWorker.getRegistrations().then(r => r.forEach(reg => reg.unregister()));
    @endif
}
</script>
</body>
</html>
