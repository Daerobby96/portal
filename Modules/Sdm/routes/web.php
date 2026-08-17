<?php

use Illuminate\Support\Facades\Route;
use Modules\Sdm\Http\Controllers\{
    SdmController,
    PresensiController,
    CutiController,
    LemburController,
    PenilaianKinerjaController,
    SuratTugasController,
    PegawaiController
};

Route::middleware(['auth'])->prefix('sdm')->name('sdm.')->group(function () {
    // Dashboard
    Route::get('/', [SdmController::class, 'index'])->name('index');

    // Pegawai (dipindahkan dari DataMaster)
    Route::get('/pegawai/search', [PegawaiController::class, 'search'])->name('pegawai.search');
    Route::get('/pegawai/download-template', [PegawaiController::class, 'downloadTemplate'])->name('pegawai.download-template');
    Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::post('/pegawai/{pegawai}/toggle-status', [PegawaiController::class, 'toggleStatus'])->name('pegawai.toggle-status');
    Route::post('/pegawai/{pegawai}/create-user', [PegawaiController::class, 'createUser'])->name('pegawai.create-user');
    Route::resource('pegawai', PegawaiController::class);

    // Presensi
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    Route::resource('presensi', PresensiController::class);

    // Cuti
    Route::post('/cuti/{cuti}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
    Route::post('/cuti/{cuti}/reject', [CutiController::class, 'reject'])->name('cuti.reject');
    Route::resource('cuti', CutiController::class);

    // Lembur
    Route::post('/lembur/{lembur}/approve', [LemburController::class, 'approve'])->name('lembur.approve');
    Route::post('/lembur/{lembur}/reject', [LemburController::class, 'reject'])->name('lembur.reject');
    Route::resource('lembur', LemburController::class)->except(['edit', 'update']);

    // Penilaian Kinerja
    Route::post('/penilaian-kinerja/{penilaian_kinerja}/submit', [PenilaianKinerjaController::class, 'submit'])->name('penilaian-kinerja.submit');
    Route::post('/penilaian-kinerja/{penilaian_kinerja}/verify', [PenilaianKinerjaController::class, 'verify'])->name('penilaian-kinerja.verify');
    Route::resource('penilaian-kinerja', PenilaianKinerjaController::class);

    // Surat Tugas
    Route::get('/surat-tugas/{surat_tugas}/pdf', [SuratTugasController::class, 'pdf'])->name('surat-tugas.pdf');
    Route::get('/surat-tugas/{surat_tugas}/sppd-pdf', [SuratTugasController::class, 'sppdPdf'])->name('surat-tugas.sppd-pdf');
    Route::post('/surat-tugas/{surat_tugas}/approve', [SuratTugasController::class, 'approve'])->name('surat-tugas.approve');
    Route::post('/surat-tugas/{surat_tugas}/reject', [SuratTugasController::class, 'reject'])->name('surat-tugas.reject');
    Route::post('/surat-tugas/{surat_tugas}/complete', [SuratTugasController::class, 'complete'])->name('surat-tugas.complete');
    Route::resource('surat-tugas', SuratTugasController::class);
});
