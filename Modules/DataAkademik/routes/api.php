<?php

use Illuminate\Support\Facades\Route;
use Modules\DataAkademik\Http\Controllers\DataAkademikController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('dataakademiks', DataAkademikController::class)->names('dataakademik');
});
