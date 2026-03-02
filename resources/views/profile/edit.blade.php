@if(auth()->user()->hasRole('Employee'))
<x-layouts.employee page-title="Profile" page-description="Manage your personal information and account security.">
<style>
/* ── Profile Page ─────────────────────────────── */
.profile-hero {
    background: linear-gradient(135deg, var(--brand) 0%, #2d6db5 100%);
    border-radius: var(--radius-xl);
    padding: 28px 24px;
    color: #fff;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='28'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}
.profile-avatar {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 3px solid rgba(255,255,255,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    color: #fff;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.profile-name {
    font-size: 1.15rem;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    margin-bottom: 2px;
}
.profile-role-badge {
    display: inline-block;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 99px;
    backdrop-filter: blur(4px);
}
.profile-meta {
    font-size: 0.80rem;
    opacity: 0.78;
    margin-top: 3px;
}

/* ── Section Cards ────────────────────────────── */
.profile-card {
    background: #fff;
    border-radius: var(--radius-xl);
    border: 1px solid var(--line);
    box-shadow: var(--shadow-soft);
    margin-bottom: 16px;
    overflow: hidden;
}
.profile-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--line);
}
.profile-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.icon-blue  { background: #e8f0fb; color: var(--brand); }
.icon-green { background: #e6f7ef; color: #16a34a; }
.icon-red   { background: #fde8e8; color: #dc2626; }
.profile-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a2a3a;
    margin: 0;
    font-family: 'Sora', sans-serif;
}
.profile-card-subtitle {
    font-size: 0.75rem;
    color: #8a96a8;
    margin: 1px 0 0;
}
.profile-card-body {
    padding: 20px;
}

/* ── Password field toggle ────────────────────── */
.pw-wrap {
    position: relative;
}
.pw-wrap .form-control {
    padding-right: 42px;
    height: 48px; /* Taller inputs */
    border-radius: 12px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}
.pw-wrap .form-control:focus {
    background-color: #fff;
    border-color: var(--brand);
    box-shadow: 0 0 0 4px rgba(var(--brand-rgb), 0.1);
}
.pw-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.pw-toggle:hover {
    background-color: #f1f5f9;
    color: var(--brand);
}
/* ── Alert banners ────────────────────────────── */
.profile-alert {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.82rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}
.profile-alert.success { background: #e6f7ef; border: 1px solid #6ee7b7; color: #065f46; }
.profile-alert.error   { background: #fde8e8; border: 1px solid #fca5a5; color: #991b1b; }

/* ── Danger card ──────────────────────────────── */
.danger-zone-card {
    background: #fff;
    border-radius: var(--radius-xl);
    border: 1.5px solid #fca5a5;
    box-shadow: 0 2px 8px rgba(220,38,38,0.07);
    overflow: hidden;
    margin-bottom: 16px;
}
.danger-zone-card .profile-card-header {
    border-bottom-color: #fde8e8;
    background: #fff9f9;
}
.btn-danger-soft {
    background: #dc2626;
    color: #fff;
    border: none;
    border-radius: 99px;
    padding: 9px 22px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .18s;
}
.btn-danger-soft:hover { background: #b91c1c; }
</style>

{{-- ── Status Flashes ───────────────────────── --}}
@if(session('status') === 'profile-updated')
<div class="profile-alert success">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l2.5 2.5L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Profile updated successfully.
</div>
@endif
@if(session('status') === 'password-updated')
<div class="profile-alert success">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l2.5 2.5L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Password changed successfully.
</div>
@endif
@if($errors->any())
<div class="profile-alert error">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    {{ $errors->first() }}
</div>
@endif

{{-- ── Hero Card ────────────────────────────── --}}
@php
    $initials = collect(explode(' ', $user->name))->map(fn($w)=>strtoupper($w[0]))->take(2)->implode('');
    $dept = $user->employee?->department?->name ?? 'Employee';
    $branch = $user->employee?->branch?->name ?? '';
@endphp
<div class="profile-hero">
    <div style="display:flex;align-items:center;gap:16px;position:relative;z-index:1;">
        <div class="profile-avatar">{{ $initials }}</div>
        <div>
            <div class="profile-name">{{ $user->name }}</div>
            <div style="margin-top:4px;">
                <span class="profile-role-badge">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;margin-top:-1px"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="currentColor"/></svg>
                    {{ $dept }}{{ $branch ? ' · '.$branch : '' }}
                </span>
            </div>
            <div class="profile-meta">{{ $user->email }}</div>
        </div>
    </div>
</div>

{{-- ── Profile Information ──────────────────── --}}
<div class="profile-card">
    <div class="profile-card-header">
        <div class="profile-card-icon icon-blue">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="currentColor"/></svg>
        </div>
        <div>
            <div class="profile-card-title">Profile Information</div>
            <div class="profile-card-subtitle">Update your name, email, and phone number</div>
        </div>
    </div>
    <div class="profile-card-body">
        <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
            @csrf
            @method('PATCH')
            <div class="col-md-6">
                <label class="input-label">Full Name</label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Your full name" required>
            </div>
            <div class="col-md-6">
                <label class="input-label">Email Address</label>
                <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="your@email.com" required>
            </div>
            <div class="col-md-6">
                <label class="input-label">Phone Number</label>
                <input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 012 345 678">
            </div>
            <div class="col-12">
                <button type="submit" class="btn-brand" style="min-width:140px;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" style="display:inline;margin-right:6px;margin-top:-2px;vertical-align:middle"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Update Password ──────────────────────── --}}
<div class="profile-card" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
    <div class="profile-card-header">
        <div class="profile-card-icon icon-green">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </div>
        <div>
            <div class="profile-card-title">Update Password</div>
            <div class="profile-card-subtitle">Use a strong password with at least 8 characters</div>
        </div>
    </div>
    <div class="profile-card-body">
        <form method="POST" action="{{ route('password.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <label class="input-label">Current Password</label>
                <div class="pw-wrap">
                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" class="form-control" placeholder="Current password" required>
                    <button type="button" class="pw-toggle" @click="showCurrent=!showCurrent">
                        <svg x-show="!showCurrent" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                        <svg x-show="showCurrent" style="display:none" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M17.94 17.94A10.94 10.94 0 0112 20C5 20 1 12 1 12a18.09 18.09 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.1 18.1 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="input-label">New Password</label>
                <div class="pw-wrap">
                    <input :type="showNew ? 'text' : 'password'" name="password" class="form-control" placeholder="New password" required>
                    <button type="button" class="pw-toggle" @click="showNew=!showNew">
                        <svg x-show="!showNew" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                        <svg x-show="showNew" style="display:none" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M17.94 17.94A10.94 10.94 0 0112 20C5 20 1 12 1 12a18.09 18.09 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.1 18.1 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="input-label">Confirm New Password</label>
                <div class="pw-wrap">
                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                    <button type="button" class="pw-toggle" @click="showConfirm=!showConfirm">
                        <svg x-show="!showConfirm" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                        <svg x-show="showConfirm" style="display:none" width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M17.94 17.94A10.94 10.94 0 0112 20C5 20 1 12 1 12a18.09 18.09 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.1 18.1 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn-brand" style="min-width:160px;background:linear-gradient(135deg,#16a34a,#15803d);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" style="display:inline;margin-right:6px;margin-top:-2px;vertical-align:middle"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Change Password
                </button>
            </div>
        </form>
    </div>
</div>


</x-layouts.employee>
@else
<x-layouts.admin>
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Administrator Profile</h2>
        <p class="mt-1 text-sm text-slate-500">Manage your account details and security settings.</p>
    </div>

    <section id="profile-section" class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="text-lg font-semibold text-slate-800">Profile Information</h3>
            <p class="text-sm text-slate-500">Update your name, email, and phone number.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input name="name" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('name', $user->name) }}" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input name="email" type="email" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('email', $user->email) }}" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                <input name="phone" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="md:col-span-2 pt-2">
                <button class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Profile
                </button>
            </div>
        </form>
    </section>

    <section id="password-section" class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="text-lg font-semibold text-slate-800">Password & Security</h3>
            <p class="text-sm text-slate-500">Use a strong password with at least 8 characters.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Current Password</label>
                <input type="password" name="current_password" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">New Password</label>
                <input type="password" name="password" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div class="md:col-span-3 pt-2">
                <button class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    Change Password
                </button>
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-red-700">Delete Account</h3>
        <p class="mt-1 text-sm text-red-600">This action is permanent and cannot be undone.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3" data-confirm="true">
            @csrf
            @method('DELETE')

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-red-700">Confirm Password</label>
                <input type="password" name="password" class="w-full rounded-lg border-red-300 text-sm shadow-sm focus:border-red-500 focus:ring-red-500" required>
            </div>

            <div class="flex items-end">
                <button class="inline-flex w-full items-center justify-center rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Delete Account
                </button>
            </div>
        </form>
    </section>
</x-layouts.admin>
@endif
