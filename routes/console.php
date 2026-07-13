<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Otomatisasi PPEPP: Cek Dokumen Kadaluarsa (Penetapan)
Schedule::call(function () {
    \App\Models\Dokumen::where('status', 'approved')
        ->where('tanggal_kadaluarsa', now()->addDays(30))
        ->each(function($doc) {
            // Logika notifikasi bisa ditambahkan di sini
            \Illuminate\Support\Facades\Log::info("Dokumen {$doc->judul} akan kadaluarsa dalam 30 hari.");
        });
})->dailyAt('08:00');

// Otomatisasi PPEPP: Cek Temuan Overdue (Pengendalian)
Schedule::call(function () {
    \App\Models\Temuan::where('status', 'open')
        ->where('batas_tindak_lanjut', '<', now())
        ->update(['status' => 'overdue' ?? 'open']); // Tambahkan logika status jika perlu
})->dailyAt('09:00');

// Kirim Notifikasi Deadline Tindak Lanjut (H-7, H-3, H-0)
Schedule::command('spmi:send-deadline-notifications')->dailyAt('07:00');

// Kirim Pengingat Deadline Tindak Lanjut Rapat (H-3, H-0, H+1)
Schedule::command('spmi:rapat-reminders')->dailyAt('07:30');

// Cek status kedaluwarsa Kerjasama
Schedule::command('kerjasama:check-expiry')->dailyAt('08:30');
