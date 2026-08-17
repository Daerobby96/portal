<?php

use Illuminate\Support\Facades\Route;
use Modules\DataMaster\Http\Controllers\ProgramStudiController;
use Modules\DataMaster\Http\Controllers\PeriodeController;
use Modules\DataMaster\Http\Controllers\UnitKerjaController;
use Modules\DataMaster\Http\Controllers\JabatanController;
use Modules\DataMaster\Http\Controllers\RuanganController;

// ── Master Data Institusi ──────────────────────────────
Route::middleware('role:super_admin,pimpinan')->group(function () {
    Route::resource('periode', PeriodeController::class);
    Route::post('periode/{periode}/activate', [PeriodeController::class, 'activate'])
        ->name('periode.activate');
    Route::resource('program-studi', ProgramStudiController::class);
    
    // Master Unit Kerja, Jabatan, & Ruangan
    Route::resource('unit-kerja', UnitKerjaController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('ruangan', RuanganController::class);
});
