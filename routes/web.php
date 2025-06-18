<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FeatureUserController;
use App\Http\Controllers\MyTasksController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubdepartmentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')
    ->middleware(['auth'])
    ->name('welcome');

Route::redirect('/dashboard', '/projects')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth'])
    ->name('projects.create');

Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth'])
    ->name('projects.edit');

Route::put('/projects/{project}', [ProjectController::class, 'update'])
    ->middleware(['auth'])
    ->name('projects.update');

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

Route::delete('/projects/{project}/media/{media}', [MediaController::class, 'destroy'])
    ->name('projects.media.destroy');

// Public Project Features Routes (Accessible by Project Creator/Assigned Users)
Route::middleware(['auth'])->group(function () {
    // Nested resource for features under projects
    // Routes will be like: /projects/{project}/features, /projects/{project}/features/create, etc.
    Route::resource('projects.features', FeatureController::class); // No 'except' for now, full CRUD
    Route::resource('projects.features.comments', CommentController::class)->only(['store', 'destroy']);
    Route::post('/feature-user/assign', [FeatureUserController::class, 'assign'])->name('feature-user.assign');
    Route::post('/feature-user/remove', [FeatureUserController::class, 'remove'])->name('feature-user.remove');
    Route::post('/feature/assign-status', [FeatureController::class, 'assignStatus'])->name('feature.assignStatus');

});

Route::get('/my-tasks', [MyTasksController::class, 'index'])
    ->middleware(['auth'])
    ->name('my-tasks');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::resource('departments', DepartmentController::class)
        ->except(['show']);
    Route::resource('subdepartments', SubdepartmentController::class)
        ->except(['show']);
});

require __DIR__.'/auth.php';
