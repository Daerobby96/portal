<?php

use Illuminate\Support\Facades\Route;
use Modules\Tridharma\Http\Controllers\PenelitianController;
use Modules\Tridharma\Http\Controllers\PengabdianController;
use Modules\Tridharma\Http\Controllers\PublikasiController;

Route::middleware(['auth', 'role:super_admin,pimpinan,auditor,auditee'])->group(function () {
    Route::resource('penelitian', PenelitianController::class);
    Route::resource('pengabdian', PengabdianController::class);
    Route::resource('publikasi', PublikasiController::class);
    Route::resource('hki', \Modules\Tridharma\Http\Controllers\HkiController::class);
});
