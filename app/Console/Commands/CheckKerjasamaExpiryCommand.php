<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Kerjasama\Models\Kerjasama;
use Carbon\Carbon;

class CheckKerjasamaExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kerjasama:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek kerjasama yang akan atau telah kedaluwarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Update expired to Kedaluwarsa
        $expiredCount = Kerjasama::where('status', 'Aktif')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', $today)
            ->update(['status' => 'Kedaluwarsa']);
            
        $this->info("Berhasil memperbarui $expiredCount kerjasama menjadi Kedaluwarsa.");
        
        // Find expiring within 60 days
        $expiring = Kerjasama::where('status', 'Aktif')
            ->whereNotNull('tanggal_selesai')
            ->whereBetween('tanggal_selesai', [$today, $today->copy()->addDays(60)])
            ->get();
            
        // In a real app, send notification here to admins.
        $this->info("Ditemukan {$expiring->count()} kerjasama yang akan kedaluwarsa dalam 60 hari.");
    }
}
