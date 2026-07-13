<?php

use Illuminate\Support\Facades\Route;
use Modules\DataMaster\Http\Controllers\ProgramStudiController;
use Modules\DataMaster\Http\Controllers\PeriodeController;

// ── Master Program Studi & Periode ──────────────────────────────
Route::middleware('role:super_admin,pimpinan')->group(function () {
    Route::resource('periode', PeriodeController::class);
    Route::post('periode/{periode}/activate', [PeriodeController::class, 'activate'])
        ->name('periode.activate');
    Route::resource('program-studi', ProgramStudiController::class);
});

