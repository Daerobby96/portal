<?php

use Illuminate\Support\Facades\Route;
use Modules\DataAkademik\Http\Controllers\MahasiswaController;
use Modules\DataAkademik\Http\Controllers\PrestasiController;
use Modules\DataAkademik\Http\Controllers\AlumniController;

Route::middleware(['auth'])->group(function () {
    Route::get('mahasiswa/template', [MahasiswaController::class, 'downloadTemplate'])->name('mahasiswa.template');
    Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
    Route::get('mahasiswa/statistik-iku', [MahasiswaController::class, 'statistikIku'])->name('mahasiswa.statistik-iku');
    Route::resource('mahasiswa', MahasiswaController::class);
    
    Route::resource('prestasi', PrestasiController::class);
    
    Route::get('alumni', [AlumniController::class, 'index'])->name('alumni.index');
});
