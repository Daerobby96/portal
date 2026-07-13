<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ─── Public Dashboard ──────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/documents', [PublicController::class, 'documents'])->name('home.documents');

// ─── Auth ──────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Portal App Launcher
    Route::get('/portal', [PortalController::class, 'index'])->name('portal');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/set-periode', [DashboardController::class, 'setPeriode'])->name('set-periode');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::get('/ppepp', [\Modules\Spmi\Http\Controllers\PpeppController::class, 'index'])->name('ppepp.index');

    // ── Profile & Settings ───────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        
        // TEST: Tailwind version
        Route::get('/edit-tailwind', function() {
            $user = auth()->user();
            return view('profile.edit-tailwind', compact('user'));
        })->name('edit.tailwind');
    });

    // ── Notifications ────────────────────────────────────────────────
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // ── Audit Mutu Internal (Dipindah ke Modul Spmi) ────────────────────────────────────────

    // ── Dokumen & Standar (Dipindah ke Modul Spmi) ──────────────────────────────

    // ── Monitoring & Evaluasi (Dipindah ke Modul Spmi) ──────────────────────────────────────
    // ── Kuesioner & IKU (Dipindah ke Modul Spmi) ──────────────────────────────────
    // ── Laporan & AI (Dipindah ke Modul Spmi) ──────────────────────────────────
});

