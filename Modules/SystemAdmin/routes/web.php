<?php

use Illuminate\Support\Facades\Route;
use Modules\SystemAdmin\Http\Controllers\UserController;
use Modules\SystemAdmin\Http\Controllers\SettingController;
use Modules\SystemAdmin\Http\Controllers\RoleController;
use Modules\SystemAdmin\Http\Controllers\ActivityLogController;

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Users Management
    Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    
    // Roles & Permissions Management
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // System & Institution Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');
    Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Activity Log & Audit Trail
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
});
