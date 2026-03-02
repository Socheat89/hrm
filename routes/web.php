<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\AttendanceQrController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\PanelController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->hasRole('Employee')) {
        return redirect()->route('employee.dashboard');
    }

    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('Employee')) {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->as('admin.')->middleware('role:Super Admin,Admin / HR')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('leave-types', LeaveTypeController::class)->except(['show']);

        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::patch('/leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.status');

        Route::get('/overtime-requests', [App\Http\Controllers\Admin\OvertimeRequestController::class, 'index'])->name('overtime-requests.index');
        Route::patch('/overtime-requests/{overtimeRequest}/status', [App\Http\Controllers\Admin\OvertimeRequestController::class, 'updateStatus'])->name('overtime-requests.status');

        Route::get('/change-dayoff-requests', [App\Http\Controllers\Admin\ChangeDayoffRequestController::class, 'index'])->name('change-dayoff-requests.index');
        Route::patch('/change-dayoff-requests/{changeDayoffRequest}/status', [App\Http\Controllers\Admin\ChangeDayoffRequestController::class, 'updateStatus'])->name('change-dayoff-requests.status');

        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::get('/payrolls/generate', [PayrollController::class, 'create'])->name('payrolls.create');
        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
        Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');
        Route::patch('/payrolls/{payroll}/paid', [PayrollController::class, 'markPaid'])->name('payrolls.paid');
        Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'download'])->name('payrolls.download');

        Route::get('/attendance-qr', [AttendanceQrController::class, 'index'])->name('attendance-qr.index');
        Route::post('/attendance-qr', [AttendanceQrController::class, 'generate'])->name('attendance-qr.generate');
        Route::get('/attendance-qr/{token}/image', [AttendanceQrController::class, 'qr'])->name('attendance-qr.image');

        Route::resource('schedules', ScheduleController::class)->except(['show']);

        Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::resource('branches', BranchController::class);
    });

    Route::prefix('employee')->as('employee.')->middleware('role:Employee')->group(function () {
        Route::get('/dashboard', PanelController::class)->name('dashboard');

        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('/attendance/scan', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

        Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
        Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    
    // OT and Day-off routes
    Route::post('/overtime/store', [App\Http\Controllers\Employee\OvertimeRequestController::class, 'store'])->name('overtime.store');
    Route::post('/changedayoff/store', [App\Http\Controllers\Employee\ChangeDayoffRequestController::class, 'store'])->name('changedayoff.store');    
    Route::get('/salary', [SalaryController::class, 'index'])->name('salary.index');        Route::get('/salary/{payroll}/download', [SalaryController::class, 'download'])->name('salary.download');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{user}/photo', ProfilePhotoController::class)->name('users.photo');
});

require __DIR__.'/auth.php';

// Design V2 preview routes (non-destructive, placed after main routes)
Route::middleware(['auth'])->group(function () {
    Route::get('/design-v2/dashboard', function () {
        return view('design_v2.dashboard');
    })->name('designv2.dashboard');

    Route::get('/design-v2/employees', function () {
        return view('design_v2.employees.index');
    })->name('designv2.employees.index');
});
