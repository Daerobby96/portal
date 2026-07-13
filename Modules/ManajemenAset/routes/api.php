<?php

use Illuminate\Support\Facades\Route;
use Modules\ManajemenAset\Http\Controllers\ManajemenAsetController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('manajemenasets', ManajemenAsetController::class)->names('manajemenaset');
});
