<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;


use App\Models\User;
use Inertia\Inertia;

Route::get('/', function () {
    
    if (User::count() === 0) {
        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    }

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Equipment Routes
    Route::resource('equipment', EquipmentController::class);
    Route::post('equipment/{equipment}/archive', [EquipmentController::class, 'archive'])->name('equipment.archive');
    Route::post('equipment/{equipment}/restore', [EquipmentController::class, 'restore'])->name('equipment.restore');

    // Request Routes
    Route::resource('requests', RequestController::class);
    Route::post('requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::post('requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');

    // Activity Logs
    Route::resource('activity-logs', ActivityLogController::class, ['only' => ['index', 'show']]);

    // Admin Routes (Super Admin only)
    Route::middleware([\App\Http\Middleware\IsSuperAdmin::class])->group(function () {
        Route::get('admin/users', [AdminController::class, 'indexUsers'])->name('admin.users.index');
        Route::get('admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::post('admin/users/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
        Route::post('admin/users/{user}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
    });
});

require __DIR__.'/settings.php';
