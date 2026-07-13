<?php

use Illuminate\Support\Facades\Route;
use Modules\Sdm\Http\Controllers\SdmController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sdms', SdmController::class)->names('sdm');
});
