<?php

use Illuminate\Support\Facades\Route;
use Modules\Spmi\Http\Controllers\SpmiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('spmis', SpmiController::class)->names('spmi');
});
