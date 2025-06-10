<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubdepartmentController;
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

Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show');

Route::post('/projects/{project}/invite', [ProjectController::class, 'inviteUser'])
    ->middleware(['auth'])
    ->name('projects.inviteUser');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::resource('departments', DepartmentController::class)
        ->except(['show']);
    Route::resource('subdepartments', SubdepartmentController::class)
        ->except(['show']);
});

require __DIR__.'/auth.php';
