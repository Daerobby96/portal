<?php

use Illuminate\Support\Facades\Route;
use Modules\TracerStudy\Http\Controllers\TracerStudyController;

Route::middleware(['auth', 'role:super_admin,pimpinan,auditor,auditee'])->group(function () {
    Route::get('tracer-study', [TracerStudyController::class, 'index'])->name('tracer-study.index');
    Route::post('tracer-study/import', [TracerStudyController::class, 'import'])->name('tracer-study.import');
    Route::get('tracer-study/template', [TracerStudyController::class, 'downloadTemplate'])->name('tracer-study.template');
    Route::post('tracer-study/sync-ppepp', [TracerStudyController::class, 'syncPpepp'])->name('tracer-study.sync-ppepp');
    Route::delete('tracer-study/{tracerStudy}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');
});
