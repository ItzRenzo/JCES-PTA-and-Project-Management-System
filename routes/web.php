<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrincipalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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
