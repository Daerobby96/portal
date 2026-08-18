<?php

use Illuminate\Support\Facades\Route;
use Modules\Spmi\Http\Controllers\AuditController;
use Modules\Spmi\Http\Controllers\TemuanController;
use Modules\Spmi\Http\Controllers\TindakLanjutController;
use Modules\Spmi\Http\Controllers\RtmController;
use Modules\Spmi\Http\Controllers\StandarController;
use Modules\Spmi\Http\Controllers\DokumenController;
use Modules\Spmi\Http\Controllers\KategoriController;
use Modules\Spmi\Http\Controllers\MonitoringController;
use Modules\Spmi\Http\Controllers\EvaluasiController;
use Modules\Spmi\Http\Controllers\IndikatorKinerjaController;
use Modules\Spmi\Http\Controllers\KuesionerController;
use Modules\Spmi\Http\Controllers\UserKuesionerController;
use Modules\Spmi\Http\Controllers\DosenKinerjaController;
use Modules\Spmi\Http\Controllers\IkuResmiController;
use Modules\Spmi\Http\Controllers\LaporanController;
use Modules\Spmi\Http\Controllers\AiController;
use Modules\Spmi\Http\Controllers\IntegrationDashboardController;
use Modules\Spmi\Http\Controllers\SiklusSpmiController;
use Modules\Spmi\Http\Controllers\PeningkatanStandarController;
use Modules\Spmi\Http\Controllers\BenchmarkingController;

// ── Pengisian Kuesioner (Public / All Users - Accessible without login for public surveys) ──
Route::get('/survei/aktif', [UserKuesionerController::class, 'activeSurvey'])->name('user-kuesioner.active');
Route::get('/survei', [UserKuesionerController::class, 'index'])->name('user-kuesioner.index');
Route::get('/survei/{kuesioner}', [UserKuesionerController::class, 'fill'])->name('user-kuesioner.fill');
Route::get('/survei/{kuesioner}/isi', [UserKuesionerController::class, 'fill']);
Route::post('/survei/{kuesioner}', [UserKuesionerController::class, 'submit'])->name('user-kuesioner.submit');

Route::middleware(['auth'])->group(function () {
    // ── Siklus Mutu SPMI ─────────────────────────────────────────
    Route::middleware('role:super_admin,auditor,pimpinan')->group(function () {
        Route::resource('siklus-spmi', SiklusSpmiController::class);
        Route::post('siklus-spmi/{siklusSpmi}/close', [SiklusSpmiController::class, 'close'])->name('siklus-spmi.close');
    });

    // ── Audit Mutu Internal ────────────────────────────────────────

    Route::middleware('role:super_admin,auditor,pimpinan,auditee,kaprodi')->group(function () {
        Route::get('audit/{audit}/surat-tugas-pdf', [AuditController::class, 'suratTugasPdf'])->name('audit.surat-tugas-pdf');
        Route::get('audit/{audit}/bapa-pdf', [AuditController::class, 'bapaPdf'])->name('audit.bapa-pdf');
        Route::post('audit/{audit}/sign-bapa', [AuditController::class, 'signBapa'])->name('audit.sign-bapa');
        Route::put('audit/{audit}/desk-evaluation/{checklist}', [AuditController::class, 'updateDeskEvaluation'])->name('audit.update-desk-evaluation');
    });

    Route::middleware('role:super_admin,auditor,pimpinan')->group(function () {
        Route::post('audit/checklist-inline', [AuditController::class, 'updateChecklistInline'])->name('audit.checklist-inline');
        Route::resource('audit', AuditController::class);
        Route::post('audit/{audit}/generate-checklist', [AuditController::class, 'generateChecklist'])
            ->name('audit.generate-checklist');
        Route::put('audit/{audit}/checklist/{checklist}', [AuditController::class, 'updateChecklist'])
            ->name('audit.update-checklist');
        Route::resource('audit.temuan', TemuanController::class);
        Route::get('rtm/compile-audit-data', [RtmController::class, 'compileAuditData'])->name('rtm.compile-audit-data');
        Route::resource('rtm', RtmController::class);
        Route::get('rtm/{rtm}/pdf', [RtmController::class, 'exportPdf'])->name('rtm.pdf');
        Route::post('temuan/{temuan}/verifikasi', [TemuanController::class, 'verifikasi'])
            ->name('temuan.verifikasi');
            
        Route::post('/laporan/audit/{audit}/update-ai-summary', [AuditController::class, 'updateAiSummary'])->name('laporan.audit.update-ai-summary');
    });

    // Tindak lanjut (auditee juga bisa input)
    Route::middleware('role:super_admin,auditor,auditee,pimpinan,kaprodi')->group(function () {
        Route::resource('tindak-lanjut', TindakLanjutController::class);
    });

    // ── Dokumen & Standar (Management) ──────────────────────────────
    Route::middleware('role:super_admin,staf_dokumen')->group(function () {
        Route::get('dokumen/template', [DokumenController::class, 'downloadTemplate'])->name('dokumen.template');
        Route::post('dokumen/import', [DokumenController::class, 'import'])->name('dokumen.import');
        Route::resource('dokumen', DokumenController::class)->except(['show'])->parameter('dokumen', 'dokumen');
        Route::get('standar/template', [StandarController::class, 'downloadTemplate'])->name('standar.template');
        Route::post('standar/import', [StandarController::class, 'import'])->name('standar.import');
        Route::resource('standar', StandarController::class);
        Route::resource('kategori-dokumen', KategoriController::class);
    });

    // ── Monitoring & Evaluasi ──────────────────────────────────────
    Route::middleware('role:super_admin,auditor,auditee,pimpinan')->group(function () {
        Route::post('monitoring/sync-erp', [MonitoringController::class, 'syncErp'])->name('monitoring.sync-erp');
        Route::post('monitoring/sync-siakad', [MonitoringController::class, 'syncSiakad'])->name('monitoring.sync-siakad');
        Route::get('monitoring/template', [MonitoringController::class, 'downloadTemplate'])->name('monitoring.template');
        Route::post('monitoring/import', [MonitoringController::class, 'import'])->name('monitoring.import');
        Route::post('monitoring/inline', [MonitoringController::class, 'updateInline'])->name('monitoring.update-inline');
        Route::resource('monitoring', MonitoringController::class);
    });
    Route::middleware('role:super_admin,auditor,pimpinan')->group(function () {
        Route::post('evaluasi/generate-ai', [EvaluasiController::class, 'generateAi'])->name('evaluasi.generate-ai');
        Route::post('evaluasi/inline', [EvaluasiController::class, 'updateInline'])->name('evaluasi.update-inline');
        Route::resource('evaluasi', EvaluasiController::class);
    });

    // ── Indikator Kinerja (IKU/IKT) ────────────────────────────────
    Route::middleware('role:super_admin,pimpinan')->group(function () {
        Route::get('indikator-kinerja/template', [IndikatorKinerjaController::class, 'downloadTemplate'])->name('indikator-kinerja.template');
        Route::post('indikator-kinerja/import', [IndikatorKinerjaController::class, 'import'])->name('indikator-kinerja.import');
        Route::resource('indikator-kinerja', IndikatorKinerjaController::class);
    });

    // ── IKU Kemdiktisaintek 358/2025 ──────────────────────────
    Route::middleware('role:super_admin,pimpinan')->group(function () {
        Route::prefix('iku-resmi')->name('iku-resmi.')->group(function () {
            Route::get('/', [IkuResmiController::class, 'index'])->name('index');
            Route::get('/set-target', [IkuResmiController::class, 'setTarget'])->name('set-target');
            Route::post('/store-target', [IkuResmiController::class, 'storeTarget'])->name('store-target');
            Route::get('/analytics', [IkuResmiController::class, 'analytics'])->name('analytics');
            Route::get('/monitoring-triwulan', [IkuResmiController::class, 'monitoringTriwulan'])->name('monitoring-triwulan');
            Route::get('/laporan-triwulan', [IkuResmiController::class, 'laporanTriwulan'])->name('laporan-triwulan');
            Route::get('/{iku_resmi}', [IkuResmiController::class, 'show'])->name('show');
            Route::get('/{iku_resmi}/input', [IkuResmiController::class, 'inputData'])->name('input');
            Route::post('/{iku_resmi}/sync', [IkuResmiController::class, 'syncDataSources'])->name('sync');
            Route::post('/{iku_resmi}/store', [IkuResmiController::class, 'storeData'])->name('store-data');
            Route::post('/{iku_resmi}/calculate', [IkuResmiController::class, 'calculate'])->name('calculate');
            Route::delete('/{iku_resmi}/delete-data', [IkuResmiController::class, 'deleteData'])->name('delete-data');
            Route::post('/calculate-all', [IkuResmiController::class, 'calculateAll'])->name('calculate-all');
            Route::get('/report/view', [IkuResmiController::class, 'report'])->name('report');
        });
    });

    // ── Kuesioner (Management) ──────────────────────────────────
    Route::middleware('role:super_admin,auditor,pimpinan')->group(function () {
        Route::get('kuesioner/template', [KuesionerController::class, 'downloadTemplate'])->name('kuesioner.template');
        Route::post('kuesioner/import-siakad', [KuesionerController::class, 'importSiakad'])->name('kuesioner.import-siakad');
        Route::post('kuesioner/{kuesioner}/import', [KuesionerController::class, 'import'])->name('kuesioner.import');
        Route::resource('kuesioner', KuesionerController::class);
        Route::post('kuesioner/{kuesioner}/add-question', [KuesionerController::class, 'addQuestion'])->name('kuesioner.add-question');
        Route::delete('kuesioner-pertanyaan/{pertanyaan}', [KuesionerController::class, 'deleteQuestion'])->name('kuesioner.delete-question');
        
        // ── Kinerja Dosen (EDOM) ──────────────────────────────────
        Route::get('kinerja-dosen', [DosenKinerjaController::class, 'index'])->name('kinerja-dosen.index');
        Route::post('kinerja-dosen/import-edom', [DosenKinerjaController::class, 'importEdom'])->name('kinerja-dosen.import-edom');
        Route::get('kinerja-dosen/{kinerja}', [DosenKinerjaController::class, 'show'])->name('kinerja-dosen.show');
        Route::get('kinerja-dosen/{kinerja}/export-pdf', [DosenKinerjaController::class, 'exportIndividualPdf'])->name('kinerja-dosen.export-pdf');

        // ── Peningkatan Standar (Kaizen) & Benchmarking (Pilar 5 PPEPP) ──
        Route::resource('peningkatan-standar', PeningkatanStandarController::class);
        Route::resource('benchmarking', BenchmarkingController::class);
    });

    // ── Laporan ────────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {        
        Route::get('/',                            [LaporanController::class, 'index'])->name('index');
        Route::get('/audit',                       [LaporanController::class, 'audit'])->name('audit');
        Route::get('/dokumen',                     [LaporanController::class, 'dokumen'])->name('dokumen');
        Route::get('/monitoring',                  [LaporanController::class, 'monitoring'])->name('monitoring');
        Route::get('/tren',                        [LaporanController::class, 'tren'])->name('tren');
        Route::get('/export/pdf/{type}',           [LaporanController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel/{type}',         [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/audit/{audit}/pdf',    [LaporanController::class, 'exportAuditIndividual'])->name('export.audit.individual');
    });

    // ── AI Smart Features ───────────────────────────────────────────
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('/analyze-root-cause', [AiController::class, 'analyzeRootCause'])->name('analyze-root-cause');
        Route::post('/suggest-recommendation', [AiController::class, 'suggestRecommendation'])->name('suggest-recommendation');
        Route::post('/summarize', [AiController::class, 'summarize'])->name('summarize');
        Route::post('/audit-summary', [AiController::class, 'generateAuditSummary'])->name('audit-summary');
        Route::post('/rtm-draft', [AiController::class, 'generateRtmDraft'])->name('rtm-draft');
    });

    // ── Integrasi Data Modul ────────────────────────────────────────
    Route::middleware('role:super_admin,pimpinan,auditor')->group(function () {
        Route::get('integrasi', [IntegrationDashboardController::class, 'index'])->name('spmi.integration.dashboard');
        Route::get('integrasi/data', [IntegrationDashboardController::class, 'getData'])->name('spmi.integration.data');
        Route::get('integrasi/widget', [IntegrationDashboardController::class, 'widget'])->name('spmi.integration.widget');
    });
});

// Public Document Access (Placed after management to avoid conflict with /create)
Route::get('dokumen/{dokumen}', [DokumenController::class, 'show'])->name('dokumen.show');
Route::get('dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');

