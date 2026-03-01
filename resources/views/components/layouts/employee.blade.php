@props([
    'pageTitle' => null,
    'pageDescription' => null,
    'showPageBanner' => true,
])
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $uiCompanySetting->primary_color ?? '#1f4f82' }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>{{ $uiCompanySetting->company_name ?? config('app.name') }} - Employee</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css', true) }}" rel="stylesheet">
    <style>
        :root {
            --brand: {{ $uiCompanySetting->primary_color ?? '#1f4f82' }};
            --brand-dark: #12395f;
            --ink: #12263d;
            --muted: #607087;
            --line: #d7e2ef;
            --surface: #f3f6fb;
            --surface-soft: #eef3f8;
            --success: #1f8a52;
            --warning: #d28a00;
            --danger: #c03b3b;
            --radius-lg: 20px;
            --radius-md: 14px;
            --shadow: 0 24px 50px rgba(17, 40, 72, 0.1);
            --shadow-soft: 0 10px 22px rgba(17, 40, 72, 0.08);
        }

        * {
            box-sizing: border-box;
            font-family: 'Manrope', sans-serif;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: linear-gradient(180deg, #19324d 0, #1f3f61 230px, var(--surface) 230px, var(--surface) 100%);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 12% -10%, rgba(255, 255, 255, 0.28), transparent 38%),
                radial-gradient(circle at 92% -30%, rgba(255, 255, 255, 0.22), transparent 35%);
            z-index: -1;
        }

        .emp-shell {
            position: relative;
            max-width: 1100px;
            min-height: 100vh;
            margin: 0 auto;
            padding-bottom: 7.5rem;
        }

        .emp-topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1rem;
            background: rgba(17, 40, 72, 0.78);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .emp-topbar h6,
        .emp-topbar p {
            margin: 0;
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(150deg, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0.1));
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.35);
            flex-shrink: 0;
        }

        .brand-title {
            color: #fff;
            font-weight: 800;
            font-size: 0.95rem;
            line-height: 1.1;
            letter-spacing: 0.01em;
        }

        .brand-subtitle {
            color: rgba(231, 239, 249, 0.82);
            font-size: 0.74rem;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 0.1rem;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-shrink: 0;
        }

        .top-actions .btn {
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.38rem 0.75rem;
            line-height: 1;
            border-color: rgba(255, 255, 255, 0.36);
            color: #f7fbff;
            background: transparent;
        }

        .top-actions .btn:hover {
            border-color: rgba(255, 255, 255, 0.56);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .emp-main {
            padding: 1rem 1rem 0;
        }

        .page-banner {
            background: linear-gradient(145deg, #fff 0%, #f7fbff 100%);
            border: 1px solid #dbe6f2;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.9rem;
        }

        .page-banner h1 {
            margin: 0;
            font-size: clamp(1.05rem, 2.2vw, 1.34rem);
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .page-banner p {
            margin: 0.35rem 0 0;
            color: var(--muted);
            font-size: 0.82rem;
            max-width: 55ch;
        }

        .date-chip {
            background: #e9f2ff;
            border: 1px solid #c5daf7;
            color: #194a7a;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 0.38rem 0.72rem;
            white-space: nowrap;
            line-height: 1.15;
        }

        .app-card {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: #fff;
            box-shadow: var(--shadow-soft);
        }

        .card-soft {
            border: 1px solid #dbe6f3;
            border-radius: var(--radius-lg);
            background: linear-gradient(160deg, #fff 0%, #f8fbff 100%);
            box-shadow: var(--shadow-soft);
        }

        .card-quiet {
            border: 1px solid #d8e2ef;
            border-radius: var(--radius-md);
            background: #f5f9ff;
        }

        .section-title {
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 650;
            letter-spacing: -0.01em;
            margin: 0 0 0.8rem;
        }

        .muted {
            color: var(--muted);
        }

        .badge-soft {
            background: #e8f2ff;
            color: #204e80;
            border: 1px solid #c5daf6;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.22rem 0.6rem;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .status-pill {
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.26rem 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .status-present {
            background: #eaf8f0;
            color: var(--success);
        }

        .status-late {
            background: #fff6e5;
            color: var(--warning);
        }

        .status-absent {
            background: #fff0ef;
            color: var(--danger);
        }

        .status-leave {
            background: #eaf2ff;
            color: #285f9c;
        }

        .status-default {
            background: #ecf0f6;
            color: #55657a;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .stat-card {
            border: 1px solid #dce6f3;
            border-radius: var(--radius-md);
            background: #f8fbff;
            padding: 0.82rem;
            min-height: 92px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card .value {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            color: #19395e;
        }

        .btn-brand {
            border: 0;
            border-radius: 999px;
            background: linear-gradient(140deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-weight: 700;
            font-size: 0.86rem;
            line-height: 1;
            padding: 0.7rem 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            box-shadow: 0 12px 24px rgba(23, 61, 96, 0.2);
        }

        .btn-brand:hover {
            color: #fff;
            filter: brightness(1.05);
        }

        .btn-quiet {
            border: 1px solid #c7d8ed;
            border-radius: 999px;
            background: #fff;
            color: #26496e;
            font-weight: 700;
            font-size: 0.82rem;
            line-height: 1;
            padding: 0.63rem 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }

        .btn-quiet:hover {
            color: #1d405f;
            border-color: #adc7e2;
        }

        .input-label {
            font-size: 0.79rem;
            font-weight: 700;
            color: #2a4563;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border-color: #c6d5e8;
            background: #f8fbff;
            color: #1a324d;
            padding: 0.62rem 0.72rem;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7ba9d6;
            box-shadow: 0 0 0 0.2rem rgba(40, 112, 176, 0.14);
            background: #fff;
        }

        .floating-scan {
            position: fixed;
            left: 50%;
            bottom: 68px;
            transform: translateX(-50%);
            z-index: 1050;
        }

        .scan-button-lg {
            width: 78px;
            height: 78px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(140deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-size: 0.92rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 16px 30px rgba(22, 59, 94, 0.28);
        }

        .scan-button-lg:hover {
            color: #fff;
        }

        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1040;
            display: grid;
            grid-template-columns: repeat(var(--nav-count), 1fr);
            gap: 0.2rem;
            align-items: center;
            background: rgba(255, 255, 255, 0.96);
            border-top: 1px solid #d5dfec;
            padding: 0.35rem 0.3rem calc(0.52rem + env(safe-area-inset-bottom));
            backdrop-filter: blur(12px);
        }

        .bottom-nav a {
            display: grid;
            justify-items: center;
            gap: 0.2rem;
            padding: 0.38rem 0.2rem;
            border-radius: 12px;
            text-decoration: none;
            color: #607088;
            font-size: 0.66rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .bottom-nav a svg {
            width: 17px;
            height: 17px;
            fill: currentColor;
        }

        .bottom-nav a.active {
            color: #1a4b7a;
            background: #e9f2ff;
        }

        .pagination {
            margin-bottom: 0;
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(11, 25, 42, 0.32);
            z-index: 1080;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .loading-overlay .spinner-border {
            color: #fff;
        }

        @media (max-width: 575.98px) {
            .brand-title {
                font-size: 0.88rem;
            }

            .top-actions .btn {
                padding: 0.36rem 0.62rem;
                font-size: 0.72rem;
            }

            .page-banner {
                flex-direction: column;
                align-items: stretch;
            }

            .date-chip {
                width: fit-content;
            }
        }

        @media (min-width: 768px) {
            .emp-main {
                padding: 1.3rem 1.35rem 0;
            }

            .stat-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (min-width: 992px) {
            .emp-shell {
                padding-bottom: 8.25rem;
            }

            .emp-topbar {
                border-radius: 0 0 18px 18px;
                margin: 0 1rem;
                top: 0.85rem;
                padding-left: 1.1rem;
                padding-right: 1.1rem;
            }

            .emp-main {
                padding-top: 2rem;
            }

            .bottom-nav {
                width: min(760px, calc(100% - 2rem));
                margin: 0 auto;
                left: 50%;
                right: auto;
                transform: translateX(-50%);
                border-radius: 16px 16px 0 0;
                border: 1px solid #d4deeb;
                border-bottom: 0;
                box-shadow: var(--shadow);
                padding-bottom: 0.6rem;
            }

            .floating-scan {
                display: none;
            }
        }
    </style>
</head>
<body>
@php($showSalary = $uiCompanySetting?->payroll_enabled ?? true)
@php($navCount = $showSalary ? 5 : 4)
@php($routeName = request()->route()?->getName())
@php($routeDetails = [
    'employee.dashboard' => ['Dashboard', 'Track your attendance status and performance for today.'],
    'employee.attendance.scan' => ['Scan Attendance', 'Submit your next check-in with live location details.'],
    'employee.attendance.index' => ['My Attendance', 'Review calendar activity, logs, and monthly totals.'],
    'employee.leave.index' => ['My Requests', 'Create new leave, overtime, and dayoff requests.'],
    'employee.salary.index' => ['My Salary', 'View payroll history, details, and downloadable payslips.'],
    'profile.edit' => ['Profile', 'Manage personal details, password, and account security.'],
])
@php([$fallbackTitle, $fallbackDescription] = $routeDetails[$routeName] ?? ['Employee Panel', 'Manage daily HR actions from one workspace.'])

<div class="emp-shell">
    <header class="emp-topbar">
        <div class="brand-wrap">
            <div class="avatar-circle" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <p class="brand-title">{{ $uiCompanySetting->company_name ?? config('app.name') }}</p>
                <p class="brand-subtitle">Employee Workspace</p>
            </div>
        </div>
        <div class="top-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-sm">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm">Logout</button>
            </form>
        </div>
    </header>

    <main class="emp-main">
        @if($showPageBanner)
            <section class="page-banner">
                <div>
                    <h1>{{ $pageTitle ?? $fallbackTitle }}</h1>
                    <p>{{ $pageDescription ?? $fallbackDescription }}</p>
                </div>
                <span class="date-chip">{{ now()->format('D, M d') }}</span>
            </section>
        @endif
        {{ $slot }}
    </main>

    <nav class="bottom-nav" style="--nav-count: {{ $navCount }};">
        <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.36 1.28a.5.5 0 0 0-.72 0L1.93 7h1.57v6.5a.5.5 0 0 0 .5.5h3.5V9.5h1V14h3.5a.5.5 0 0 0 .5-.5V7h1.57z"/></svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('employee.attendance.scan') }}" class="{{ request()->routeIs('employee.attendance.scan') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 3a2 2 0 0 1 2-2h1v1H5a1 1 0 0 0-1 1v1H3zm9-1h-1V1h1a2 2 0 0 1 2 2v1h-1V3a1 1 0 0 0-1-1zM4 12a1 1 0 0 0 1 1h1v1H5a2 2 0 0 1-2-2v-1h1zm9-1h1v1a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1zM5.5 5h5A1.5 1.5 0 0 1 12 6.5v3A1.5 1.5 0 0 1 10.5 11h-5A1.5 1.5 0 0 1 4 9.5v-3A1.5 1.5 0 0 1 5.5 5zm0 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/></svg>
            <span>Scan</span>
        </a>
        <a href="{{ route('employee.attendance.index') }}" class="{{ request()->routeIs('employee.attendance.index') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M4 .5a.5.5 0 0 1 .5.5V2h7V1a.5.5 0 0 1 1 0V2h.5A1.5 1.5 0 0 1 14.5 3.5v10A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5v-10A1.5 1.5 0 0 1 3 2h.5V1A.5.5 0 0 1 4 .5M2.5 6v7.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V6z"/></svg>
            <span>Attendance</span>
        </a>
        <a href="{{ route('employee.leave.index') }}" class="{{ request()->routeIs('employee.leave.*', 'employee.overtime.*', 'employee.changedayoff.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M9.5 1a.5.5 0 0 1 .5.5V2h2A1.5 1.5 0 0 1 13.5 3.5v10A1.5 1.5 0 0 1 12 15H4A1.5 1.5 0 0 1 2.5 13.5v-10A1.5 1.5 0 0 1 4 2h2v-.5a.5.5 0 0 1 1 0V2h2v-.5a.5.5 0 0 1 .5-.5M4 3a.5.5 0 0 0-.5.5V5h9V3.5A.5.5 0 0 0 12 3z"/></svg>
            <span>Requests</span>
        </a>
        @if($showSalary)
            <a href="{{ route('employee.salary.index') }}" class="{{ request()->routeIs('employee.salary.*') ? 'active' : '' }}">
                <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 1.5a.5.5 0 0 1 .5.5v.527a2.5 2.5 0 0 1 1.93 1.24.5.5 0 1 1-.86.51A1.5 1.5 0 0 0 8 3.5c-.79 0-1.5.43-1.5 1s.71 1 1.5 1c1.49 0 2.5.87 2.5 2s-.92 1.9-2 2.06V10a.5.5 0 0 1-1 0v-.438a2.5 2.5 0 0 1-2-1.722.5.5 0 1 1 .96-.282A1.5 1.5 0 0 0 8 8.5c.97 0 1.5-.5 1.5-1S8.97 6.5 8 6.5c-1.49 0-2.5-.87-2.5-2 0-1.04.77-1.82 2-2.02V2a.5.5 0 0 1 .5-.5"/><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v1H3V2a1 1 0 0 1 1-1"/></svg>
                <span>Salary</span>
            </a>
        @endif
    </nav>

    <div class="floating-scan d-lg-none">
        <a href="{{ route('employee.attendance.scan') }}" class="scan-button-lg" aria-label="Scan Attendance">Scan</a>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay"><div class="spinner-border"></div></div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
    @if(session('status'))
        <div class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ session('status') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">{{ $errors->first() }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
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

document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirm === 'true' && !window.confirm('Are you sure you want to continue?')) {
            event.preventDefault();
            return;
        }

        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    });
});
</script>
</body>
</html>
