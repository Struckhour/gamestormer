<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth'])
    ->name('projects.create');

Route::get('/projects', [ProjectController::class, 'index'])
    ->middleware(['auth'])
    ->name('projects.index');

Route::post('/projects', [ProjectController::class, 'store'])
    ->middleware(['auth'])
    ->name('projects.store');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    // Add more admin routes here as you expand
});

require __DIR__.'/auth.php';
