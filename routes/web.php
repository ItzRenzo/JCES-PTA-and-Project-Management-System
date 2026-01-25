<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUpdateController;
use App\Http\Controllers\ContributionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    return match($user->user_type) {
        'administrator' => redirect()->route('administrator.dashboard'),
        'principal' => redirect()->route('principal.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'parent' => view('dashboard'),
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

Route::get('/principal/announcements', function () {
    return view('principal.announcements.index');
})->middleware(['auth', 'verified'])->name('principal.announcements');

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

Route::get('/principal/reports/payments', [ReportsController::class, 'paymentsReport'])
    ->middleware(['auth', 'verified'])
    ->name('principal.reports.payments');

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

// Principal Project Management routes
Route::get('/principal/projects', [ProjectController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.index');

Route::get('/principal/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.create');

Route::post('/principal/projects', [ProjectController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.store');

Route::get('/principal/projects/{projectID}', [ProjectController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.show');

Route::get('/principal/projects/{projectID}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.edit');

Route::put('/principal/projects/{projectID}', [ProjectController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.update');

Route::delete('/principal/projects/{projectID}', [ProjectController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.destroy');

Route::post('/principal/projects/{projectID}/updates', [ProjectUpdateController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.updates.store');

Route::delete('/principal/projects/{projectID}/updates/{updateID}', [ProjectUpdateController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('principal.projects.updates.destroy');

Route::get('/principal/contributions', [ContributionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('principal.contributions.index');

Route::post('/principal/contributions', [ContributionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('principal.contributions.store');

Route::put('/principal/contributions/{contributionID}', [ContributionController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('principal.contributions.update');

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

Route::get('/administrator/announcements', function () {
    return view('administrator.announcements.index');
})->middleware(['auth', 'verified'])->name('administrator.announcements');

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

Route::get('/administrator/reports/payments', [ReportsController::class, 'paymentsReport'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.reports.payments');

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

// Administrator Project Management routes
Route::get('/administrator/projects', [ProjectController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.index');

Route::get('/administrator/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.create');

Route::post('/administrator/projects', [ProjectController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.store');

Route::get('/administrator/projects/{projectID}', [ProjectController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.show');

Route::get('/administrator/projects/{projectID}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.edit');

Route::put('/administrator/projects/{projectID}', [ProjectController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.update');

Route::delete('/administrator/projects/{projectID}', [ProjectController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.destroy');

Route::post('/administrator/projects/{projectID}/updates', [ProjectUpdateController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.updates.store');

Route::delete('/administrator/projects/{projectID}/updates/{updateID}', [ProjectUpdateController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.projects.updates.destroy');

Route::get('/administrator/payments', [ContributionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.payments.index');

Route::post('/administrator/payments', [ContributionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.payments.store');

Route::put('/administrator/payments/{contributionID}', [ContributionController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('administrator.payments.update');

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
