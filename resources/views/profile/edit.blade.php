@if(auth()->user()->hasRole('Employee'))
<x-layouts.employee page-title="Profile" page-description="Update personal information, change password, and manage account security.">
    <style>
        .danger-card {
            border-radius: 16px;
            border: 1px solid #f0c7c7;
            background: #fff3f3;
        }
    </style>

    <section id="profile-section" class="card-soft p-4 mb-4">
        <h3 class="section-title">Profile Information</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
            @csrf
            @method('PATCH')
            <div class="col-md-6">
                <label class="input-label">Name</label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="input-label">Email</label>
                <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="input-label">Phone</label>
                <input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="col-12 pt-1">
                <button class="btn-brand">Update Profile</button>
            </div>
        </form>
    </section>

    <section id="password-section" class="card-soft p-4 mb-4">
        <h3 class="section-title">Update Password</h3>
        <form method="POST" action="{{ route('password.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <label class="input-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="input-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="input-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="col-12 pt-1">
                <button class="btn-brand">Change Password</button>
            </div>
        </form>
    </section>

    <section class="danger-card p-4 mb-3">
        <h3 class="h6 fw-bold text-danger mb-3">Delete Account</h3>
        <form method="POST" action="{{ route('profile.destroy') }}" data-confirm="true" class="row g-3">
            @csrf
            @method('DELETE')
            <div class="col-md-5">
                <label class="input-label text-danger">Confirm Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-outline-danger rounded-pill px-3">Delete Account</button>
            </div>
        </form>
    </section>
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
