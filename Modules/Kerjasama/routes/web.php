<?php

use Illuminate\Support\Facades\Route;
use Modules\Kerjasama\Http\Controllers\KerjasamaController;

Route::middleware(['auth', 'role:super_admin,pimpinan,auditor,auditee'])->group(function () {
    Route::post('kerjasama/import',           [KerjasamaController::class, 'import'])->name('kerjasama.import');
    Route::get('kerjasama/template',          [KerjasamaController::class, 'downloadTemplate'])->name('kerjasama.template');
    Route::resource('kerjasama', KerjasamaController::class);
    
    // Evaluasi Mitra
    Route::post('kerjasama/{kerjasama}/evaluasi', [Modules\Kerjasama\Http\Controllers\EvaluasiMitraController::class, 'store'])->name('kerjasama.evaluasi.store');
    Route::delete('kerjasama/{kerjasama}/evaluasi/{evaluasi}', [Modules\Kerjasama\Http\Controllers\EvaluasiMitraController::class, 'destroy'])->name('kerjasama.evaluasi.destroy');
});
