<?php

use Illuminate\Support\Facades\Route;
use Modules\SystemAdmin\Http\Controllers\UserController;
use Modules\SystemAdmin\Http\Controllers\SettingController;
use Modules\SystemAdmin\Http\Controllers\ActivityLogController;

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');
    
    // Activity Log
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
});
