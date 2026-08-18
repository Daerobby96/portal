<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenAset\Http\Controllers\AsetController;
use Modules\ManajemenAset\Http\Controllers\RuanganController;
use Modules\ManajemenAset\Http\Controllers\PemeliharaanController;
use Modules\ManajemenAset\Http\Controllers\PeminjamanController;
use Modules\ManajemenAset\Http\Controllers\BookingRuanganController;
use Modules\ManajemenAset\Http\Controllers\KategoriAsetController;

Route::middleware(['auth'])->group(function () {
    
    // ── Dashboard Aset ──────────────────────────────────────────
    Route::get('/manajemen-aset', function() {
        return redirect()->route('aset.index');
    })->name('manajemen-aset.index');

    // ── Aset (Super Admin, Staff, Pimpinan) ──────────────────────
    Route::middleware('role:super_admin,staff,pimpinan')->group(function () {
        Route::resource('aset', AsetController::class);
        Route::resource('kategori-aset', KategoriAsetController::class)->except(['show']);
    });

    // ── Pemeliharaan (Super Admin, Staff, Pimpinan) ──────────────
    Route::middleware('role:super_admin,staff,pimpinan')->group(function () {
        Route::get('aset/{aset}/pemeliharaan', [PemeliharaanController::class, 'create'])->name('pemeliharaan.create');
        Route::post('aset/{aset}/pemeliharaan', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::get('pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan.index');
        Route::get('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'show'])->name('pemeliharaan.show');
        Route::get('pemeliharaan/{pemeliharaan}/edit', [PemeliharaanController::class, 'edit'])->name('pemeliharaan.edit');
        Route::put('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
    });

    // ── Peminjaman Aset (Semua User) ────────────────────────────
    Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    
    // Approval & Return (Super Admin, Staff, Pimpinan)
    Route::middleware('role:super_admin,staff,pimpinan')->group(function () {
        Route::put('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::put('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::put('peminjaman/{peminjaman}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');
    });



    // ── Booking Ruangan (Semua User) ────────────────────────────
    Route::get('booking-ruangan/create', [BookingRuanganController::class, 'create'])->name('booking-ruangan.create');
    Route::post('booking-ruangan', [BookingRuanganController::class, 'store'])->name('booking-ruangan.store');
    Route::get('booking-ruangan', [BookingRuanganController::class, 'index'])->name('booking-ruangan.index');
    Route::get('booking-ruangan/{bookingRuangan}', [BookingRuanganController::class, 'show'])->name('booking-ruangan.show');
    Route::delete('booking-ruangan/{bookingRuangan}', [BookingRuanganController::class, 'destroy'])->name('booking-ruangan.destroy');
    
    // Approval (Super Admin, Staff, Kaprodi)
    Route::middleware('role:super_admin,staff,kaprodi')->group(function () {
        Route::put('booking-ruangan/{bookingRuangan}/approve', [BookingRuanganController::class, 'approve'])->name('booking-ruangan.approve');
        Route::put('booking-ruangan/{bookingRuangan}/reject', [BookingRuanganController::class, 'reject'])->name('booking-ruangan.reject');
    });
});
