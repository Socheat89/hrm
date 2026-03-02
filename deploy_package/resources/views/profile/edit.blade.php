@if(auth()->user()->hasRole('Employee'))
<x-layouts.employee page-title="Profile" page-description="Update personal information, change password, and manage account security.">
    <style>
        .danger-card {
            border-radius: 16px;
            border: 1px solid #f0c7c7;
            background: #fff3f3;
        }
    </style>

    <section class="card-soft p-4 mb-4">
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

    <section class="card-soft p-4 mb-4">
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
    <div class="card card-soft p-3 mb-3">
        <h5 class="mb-3">Profile</h5>
        <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
            @csrf
            @method('PATCH')
            <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
            <div class="col-12"><button class="btn btn-primary">Update Profile</button></div>
        </form>
    </div>

    <div class="card card-soft p-3 mb-3">
        <h6 class="mb-3">Update Password</h6>
        <form method="POST" action="{{ route('password.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-4"><input type="password" name="current_password" class="form-control" placeholder="Current password" required></div>
            <div class="col-md-4"><input type="password" name="password" class="form-control" placeholder="New password" required></div>
            <div class="col-md-4"><input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required></div>
            <div class="col-12"><button class="btn btn-primary">Change Password</button></div>
        </form>
    </div>

    <div class="card card-soft p-3 border border-danger-subtle">
        <h6 class="text-danger mb-2">Delete Account</h6>
        <form method="POST" action="{{ route('profile.destroy') }}" class="row g-2" data-confirm="true">
            @csrf
            @method('DELETE')
            <div class="col-md-4"><input type="password" name="password" class="form-control" placeholder="Confirm password" required></div>
            <div class="col-md-4"><button class="btn btn-outline-danger">Delete Account</button></div>
        </form>
    </div>
</x-layouts.admin>
@endif
