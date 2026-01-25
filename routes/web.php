<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Redirect to appropriate dashboard based on user type
    return match($user->user_type) {
        'administrator' => redirect()->route('administrator.dashboard'),
        'principal' => redirect()->route('principal.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'parent' => view('dashboard'), // Keep parents on the generic dashboard
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Principal routes
Route::get('/principal', [PrincipalController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('principal.dashboard');

Route::get('/principal/create-account', [PrincipalController::class, 'createAccount'])
    ->middleware(['auth', 'verified'])
    ->name('principal.create-account');

Route::post('/principal/create-account', [PrincipalController::class, 'storeAccount'])
    ->middleware(['auth', 'verified'])
    ->name('principal.store-account');

Route::get('/principal/users', [PrincipalController::class, 'users'])
    ->middleware(['auth', 'verified'])
    ->name('principal.users');

Route::put('/principal/users/{id}', [PrincipalController::class, 'updateUser'])
    ->middleware(['auth', 'verified'])
    ->name('principal.users.update');

Route::delete('/principal/users/{id}', [PrincipalController::class, 'deleteUser'])
    ->middleware(['auth', 'verified'])
    ->name('principal.users.delete');

// Principal Reports routes
Route::get('/principal/reports', [ReportsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports');

Route::get('/principal/reports/activity-logs', [ReportsController::class, 'activityLogs'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.activity-logs');

Route::get('/principal/reports/security-logs', [ReportsController::class, 'securityLogs'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.security-logs');

Route::get('/principal/reports/user-activity', [ReportsController::class, 'userActivity'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.user-activity');

Route::get('/principal/reports/enrollment', [ReportsController::class, 'enrollmentStats'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.enrollment');

Route::get('/principal/reports/participation', [ReportsController::class, 'participationReport'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.participation');

Route::get('/principal/reports/project-analytics', [ReportsController::class, 'projectAnalytics'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.project-analytics');

Route::get('/principal/reports/financial-summary', [ReportsController::class, 'financialSummary'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.financial-summary');

Route::get('/principal/reports/kpis', [ReportsController::class, 'dashboardMetrics'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.kpis');

Route::get('/principal/reports/export', [ReportsController::class, 'exportLogs'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.export');

Route::get('/principal/reports/financial-export', [ReportsController::class, 'exportFinancialSummary'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.financial-export');

// Administrator routes (using same controller and views as Principal)
Route::get('/administrator', [PrincipalController::class, 'adminIndex'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.dashboard');

Route::get('/administrator/create-account', [PrincipalController::class, 'adminCreateAccount'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.create-account');

Route::post('/administrator/create-account', [PrincipalController::class, 'adminStoreAccount'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.store-account');

Route::get('/administrator/users', [PrincipalController::class, 'adminUsers'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.users');

Route::put('/administrator/users/{id}', [PrincipalController::class, 'adminUpdateUser'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.users.update');

Route::delete('/administrator/users/{id}', [PrincipalController::class, 'adminDeleteUser'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.users.delete');

// Administrator Reports routes
Route::get('/administrator/reports', [ReportsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports');

Route::get('/administrator/reports/activity-logs', [ReportsController::class, 'activityLogs'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.activity-logs');

Route::get('/administrator/reports/security-logs', [ReportsController::class, 'securityLogs'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.security-logs');

Route::get('/administrator/reports/user-activity', [ReportsController::class, 'userActivity'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.user-activity');

Route::get('/administrator/reports/enrollment', [ReportsController::class, 'enrollmentStats'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.enrollment');

Route::get('/administrator/reports/participation', [ReportsController::class, 'participationReport'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.participation');

Route::get('/administrator/reports/project-analytics', [ReportsController::class, 'projectAnalytics'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.project-analytics');

Route::get('/administrator/reports/financial-summary', [ReportsController::class, 'financialSummary'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.financial-summary');

Route::get('/administrator/reports/kpis', [ReportsController::class, 'dashboardMetrics'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.kpis');

Route::get('/administrator/reports/export', [ReportsController::class, 'exportLogs'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.export');

Route::get('/administrator/reports/financial-export', [ReportsController::class, 'exportFinancialSummary'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.financial-export');

// Teacher routes
Route::get('/teacher', [TeacherController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('teacher.dashboard');

Route::get('/teacher/create-account', [TeacherController::class, 'createAccount'])
    ->middleware(['auth', 'verified'])
    ->name('teacher.create-account');

Route::post('/teacher/create-account', [TeacherController::class, 'storeAccount'])
    ->middleware(['auth', 'verified'])
    ->name('teacher.store-account');

Route::get('/teacher/users', [TeacherController::class, 'users'])
    ->middleware(['auth', 'verified'])
    ->name('teacher.users');

Route::put('/teacher/users/{id}', [TeacherController::class, 'updateUser'])
    ->middleware(['auth', 'verified'])
    ->name('teacher.users.update');

// Logout route that redirects to login
Route::get('/sign-out', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('sign-out');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
