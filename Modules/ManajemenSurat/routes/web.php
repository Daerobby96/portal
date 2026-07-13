<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenSurat\Http\Controllers\DashboardController;
use Modules\ManajemenSurat\Http\Controllers\SuratKeputusanController;
use Modules\ManajemenSurat\Http\Controllers\SuratKeluarController;
use Modules\ManajemenSurat\Http\Controllers\SuratMasukController;
use Modules\ManajemenSurat\Http\Controllers\DisposisiController;

Route::middleware(['auth'])->group(function () {
    
    // ═══════════════════════════════════════════════════════════
    // Dashboard
    // ═══════════════════════════════════════════════════════════
    Route::get('/manajemen-surat', [DashboardController::class, 'index'])->name('manajemen-surat.dashboard');
    
    // ═══════════════════════════════════════════════════════════
    // Surat Keputusan (Backward Compatibility)
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:super_admin,pimpinan')->prefix('surat-keputusan')->name('surat-keputusan.')->group(function () {
        Route::get('/', [SuratKeputusanController::class, 'index'])->name('index');
        Route::get('/create', [SuratKeputusanController::class, 'create'])->name('create');
        Route::post('/preview', [SuratKeputusanController::class, 'preview'])->name('preview');
        Route::post('/', [SuratKeputusanController::class, 'store'])->name('store');
        Route::get('/{surat_keputusan}/download', [SuratKeputusanController::class, 'download'])->name('download');
        Route::delete('/{surat_keputusan}', [SuratKeputusanController::class, 'destroy'])->name('destroy');
    });

    // ═══════════════════════════════════════════════════════════
    // Unit Pengelola Surat
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:super_admin,pimpinan')->prefix('unit-pengelola')->name('unit-pengelola.')->group(function () {
        Route::get('/', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'create'])->name('create');
        Route::post('/', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'store'])->name('store');
        Route::get('/{unitPengelola}', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'show'])->name('show');
        Route::get('/{unitPengelola}/edit', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'edit'])->name('edit');
        Route::put('/{unitPengelola}', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'update'])->name('update');
        Route::delete('/{unitPengelola}', [\Modules\ManajemenSurat\Http\Controllers\UnitPengelolaSuratController::class, 'destroy'])->name('destroy');
    });

    // ═══════════════════════════════════════════════════════════
    // Surat Keluar
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:super_admin,pimpinan,admin_prodi,staff')->prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', [SuratKeluarController::class, 'index'])->name('index');
        Route::get('/create', [SuratKeluarController::class, 'create'])->name('create');
        Route::post('/', [SuratKeluarController::class, 'store'])->name('store');
        Route::get('/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('show');
        Route::get('/{suratKeluar}/edit', [SuratKeluarController::class, 'edit'])->name('edit');
        Route::put('/{suratKeluar}', [SuratKeluarController::class, 'update'])->name('update');
        Route::delete('/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('destroy');
        Route::get('/{suratKeluar}/download', [SuratKeluarController::class, 'download'])->name('download');
        
        // PDF Generation
        Route::get('/{suratKeluar}/pdf', [SuratKeluarController::class, 'generatePdf'])->name('pdf');
        Route::get('/{suratKeluar}/preview-pdf', [SuratKeluarController::class, 'previewPdf'])->name('preview-pdf');
        
        // Approval
        Route::post('/{suratKeluar}/approve', [SuratKeluarController::class, 'approve'])->name('approve');
        Route::post('/{suratKeluar}/reject', [SuratKeluarController::class, 'reject'])->name('reject');
    });

    // ═══════════════════════════════════════════════════════════
    // Surat Masuk
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:super_admin,pimpinan,admin_prodi,staff')->prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/', [SuratMasukController::class, 'index'])->name('index');
        Route::get('/create', [SuratMasukController::class, 'create'])->name('create');
        Route::post('/', [SuratMasukController::class, 'store'])->name('store');
        Route::get('/{suratMasuk}', [SuratMasukController::class, 'show'])->name('show');
        Route::get('/{suratMasuk}/edit', [SuratMasukController::class, 'edit'])->name('edit');
        Route::put('/{suratMasuk}', [SuratMasukController::class, 'update'])->name('update');
        Route::delete('/{suratMasuk}', [SuratMasukController::class, 'destroy'])->name('destroy');
        Route::get('/{suratMasuk}/download', [SuratMasukController::class, 'download'])->name('download');
    });

    // ═══════════════════════════════════════════════════════════
    // Disposisi
    // ═══════════════════════════════════════════════════════════
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/my-disposisi', [DisposisiController::class, 'myDisposisi'])->name('my-disposisi');
        Route::get('/{disposisi}', [DisposisiController::class, 'show'])->name('show');
        Route::post('/{disposisi}/update-status', [DisposisiController::class, 'updateStatus'])->name('update-status');
    });

    Route::middleware('role:super_admin,pimpinan,admin_prodi')->group(function () {
        Route::get('/surat-masuk/{suratMasuk}/disposisi/create', [DisposisiController::class, 'create'])->name('surat-masuk.disposisi.create');
        Route::post('/surat-masuk/{suratMasuk}/disposisi', [DisposisiController::class, 'store'])->name('surat-masuk.disposisi.store');
    });
});

