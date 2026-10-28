<?php

use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): RedirectResponse => redirect()->route(
    auth()->check() ? 'dashboard' : 'login'
));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'permission:user.view'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::resource('users', UserController::class)->except(['show']);
    });

Route::middleware(['auth', 'permission:role.view'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::put('roles', [RolePermissionController::class, 'update'])
            ->middleware('permission:role.update')
            ->name('roles.update');
    });
