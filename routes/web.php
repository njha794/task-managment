<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/members', [ProjectController::class, 'members'])->name('projects.members');
    Route::post('projects/{project}/members', [ProjectController::class, 'assignMembers'])->name('projects.members.assign');

    Route::get('projects/{project}/milestones/create', [MilestoneController::class, 'create'])->name('milestones.create');
    Route::post('projects/{project}/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::get('milestones/{milestone}/edit', [MilestoneController::class, 'edit'])->name('milestones.edit');
    Route::put('milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');

    Route::get('milestones/{milestone}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('milestones/{milestone}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('tasks/{task}/status', [TaskController::class, 'status'])->name('tasks.status');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    });
});

require __DIR__.'/auth.php';
