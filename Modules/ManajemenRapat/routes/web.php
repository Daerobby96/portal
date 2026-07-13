<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenRapat\Http\Controllers\RapatController;

Route::middleware(['auth', 'role:super_admin,pimpinan,auditor,auditee,staff'])->prefix('rapat')->name('rapat.')->group(function () {
    Route::get('/',                                     [RapatController::class, 'index'])->name('index');
    Route::get('/create',                               [RapatController::class, 'create'])->name('create');
    Route::post('/',                                    [RapatController::class, 'store'])->name('store');
    Route::get('/{rapat}',                              [RapatController::class, 'show'])->name('show');
    Route::get('/{rapat}/edit',                         [RapatController::class, 'edit'])->name('edit');
    Route::put('/{rapat}',                              [RapatController::class, 'update'])->name('update');
    Route::delete('/{rapat}',                           [RapatController::class, 'destroy'])->name('destroy');
    Route::post('/{rapat}/status',                      [RapatController::class, 'ubahStatus'])->name('ubah-status');
    Route::get('/{rapat}/export-pdf',                   [RapatController::class, 'exportPdf'])->name('export-pdf');
    // Agenda
    Route::post('/{rapat}/agenda',                      [RapatController::class, 'storeAgenda'])->name('agenda.store');
    Route::delete('/{rapat}/agenda/{agenda}',           [RapatController::class, 'destroyAgenda'])->name('agenda.destroy');
    Route::put('/{rapat}/agenda/{agenda}/notulensi',    [RapatController::class, 'updateNotulensi'])->name('agenda.notulensi');
    // Peserta
    Route::post('/{rapat}/peserta',                     [RapatController::class, 'storePeserta'])->name('peserta.store');
    Route::patch('/{rapat}/peserta/{peserta}/kehadiran',[RapatController::class, 'updateKehadiran'])->name('peserta.kehadiran');
    Route::delete('/{rapat}/peserta/{peserta}',         [RapatController::class, 'destroyPeserta'])->name('peserta.destroy');
    // Tindak Lanjut
    Route::post('/{rapat}/tindak-lanjut',                       [RapatController::class, 'storeTindakLanjut'])->name('tl.store');
    Route::patch('/{rapat}/tindak-lanjut/{tindakLanjut}',       [RapatController::class, 'updateTindakLanjut'])->name('tl.update');
    // Search peserta AJAX
    Route::get('/search-peserta',                               [RapatController::class, 'searchPeserta'])->name('search-peserta');        // Lampiran
    Route::post('/{rapat}/lampiran',                    [RapatController::class, 'storeLampiran'])->name('lampiran.store');
    Route::delete('/{rapat}/lampiran/{lampiran}',       [RapatController::class, 'destroyLampiran'])->name('lampiran.destroy');
    Route::get('/{rapat}/lampiran/{lampiran}/download',  [RapatController::class, 'downloadLampiran'])->name('lampiran.download');
});

