<?php

use App\Http\Controllers\Admin\OpdController;
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

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('opds/data', [OpdController::class, 'data'])
            ->middleware('permission:opd.view')
            ->name('opds.data');
        Route::get('opds', [OpdController::class, 'index'])
            ->middleware('permission:opd.view')
            ->name('opds.index');
        Route::get('opds/create', [OpdController::class, 'create'])
            ->middleware('permission:opd.create')
            ->name('opds.create');
        Route::post('opds', [OpdController::class, 'store'])
            ->middleware('permission:opd.create')
            ->name('opds.store');
        Route::get('opds/{opd}/edit', [OpdController::class, 'edit'])
            ->middleware('permission:opd.update')
            ->name('opds.edit');
        Route::put('opds/{opd}', [OpdController::class, 'update'])
            ->middleware('permission:opd.update')
            ->name('opds.update');
        Route::delete('opds/{opd}', [OpdController::class, 'destroy'])
            ->middleware('permission:opd.delete')
            ->name('opds.destroy');
    });

Route::middleware(['auth', 'permission:user.view'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('users/data', [UserController::class, 'data'])
            ->middleware('permission:user.view')
            ->name('users.data');
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
