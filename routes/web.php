<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LeaderController;

use App\Http\Controllers\Leader\LeaderDashboardController;
use App\Http\Controllers\Leader\LeaderEmployeeController;
use App\Http\Controllers\Leader\LeaderAttendanceController;
use App\Http\Controllers\Leader\LeaderReportController;

use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\EmployeeHistoryController;

use Illuminate\Support\Facades\Route;

Route::middleware('office.network')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.process');

        Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequest'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendTemporaryPassword'])->name('password.email');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/email/send-otp', [ProfileController::class, 'sendEmailOtp'])
            ->name('profile.email.sendOtp');

        Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            Route::resource('employees', EmployeeController::class);
            Route::resource('leaders', LeaderController::class);
            Route::resource('divisions', DivisionController::class);

            Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index');
            Route::get('/attendances/{attendance}', [AdminAttendanceController::class, 'show'])->name('attendances.show');

            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');

            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::post('/admin/settings/reset-default', [SettingController::class, 'resetDefault'])->name('admin.settings.resetDefault');
        });

        Route::middleware('role:leader')->prefix('leader')->name('leader.')->group(function () {
            Route::get('/dashboard', [LeaderDashboardController::class, 'index'])->name('dashboard');

            Route::get('/employees', [LeaderEmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/{employee}', [LeaderEmployeeController::class, 'show'])->name('employees.show');

            Route::get('/attendances', [LeaderAttendanceController::class, 'index'])->name('attendances.index');
            Route::get('/attendances/{attendance}', [LeaderAttendanceController::class, 'show'])->name('attendances.show');
            Route::put('/attendances/{attendance}/reject', [LeaderAttendanceController::class, 'reject'])->name('attendances.reject');

            Route::get('/reports', [LeaderReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/print', [LeaderReportController::class, 'print'])->name('reports.print');
        });

        Route::middleware('role:employee')->prefix('employee')->name('employee.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'employee'])->name('dashboard');

            Route::get('/check-in', [AttendanceController::class, 'checkInForm'])->name('checkin.form');
            Route::post('/check-in', [AttendanceController::class, 'checkInStore'])->name('checkin.store');

            Route::get('/check-out', [AttendanceController::class, 'checkOutForm'])->name('checkout.form');
            Route::post('/check-out', [AttendanceController::class, 'checkOutStore'])->name('checkout.store');

            Route::get('/history', [EmployeeHistoryController::class, 'index'])->name('history.index');
            Route::get('/history/{attendance}', [EmployeeHistoryController::class, 'show'])->name('history.show');
        });
    });
});