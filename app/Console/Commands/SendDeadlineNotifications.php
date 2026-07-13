<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Temuan;
use App\Models\User;
use App\Notifications\DeadlineTindakLanjutNotification;
use Carbon\Carbon;

class SendDeadlineNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spmi:send-deadline-notifications {--dry-run : Only show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi untuk temuan yang mendekati deadline tindak lanjut (H-7, H-3, H-0)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa deadline tindak lanjut temuan...');

        $now = Carbon::now();
        $isDryRun = $this->option('dry-run');

        // Cari temuan yang belum selesai tindak lanjutnya (status open)
        // Asumsi: Jika temuan memiliki tindak lanjut yang sudah disetujui, maka statusnya bukan open lagi
        // Untuk contoh ini, kita asumsikan status != 'closed' atau 'selesai'
        
        $temuans = Temuan::whereNotNull('batas_tindak_lanjut')
                         ->whereNotIn('status', ['closed', 'selesai', 'ditutup'])
                         ->get();

        $count = 0;

        foreach ($temuans as $temuan) {
            $deadline = Carbon::parse($temuan->batas_tindak_lanjut);
            $daysLeft = $now->diffInDays($deadline, false); // false for negative values if past due

            // Kirim notifikasi pada H-7, H-3, dan H-0
            // Karena command dijalankan harian, kita paskan dengan hari tersebut
            if (in_array(ceil($daysLeft), [7, 3, 0])) {
                
                $auditee = $temuan->audit->auditee ?? null;
                $auditor = $temuan->audit->auditor ?? null;

                $this->info("Menemukan temuan #{$temuan->id} ({$temuan->deskripsi}) - Sisa waktu: " . ceil($daysLeft) . " hari");

                if (!$isDryRun) {
                    // Jika Anda menggunakan Notification facade
                    /*
                    if ($auditee) {
                        $auditee->notify(new DeadlineTindakLanjutNotification($temuan, ceil($daysLeft)));
                    }
                    if ($auditor) {
                        $auditor->notify(new DeadlineTindakLanjutNotification($temuan, ceil($daysLeft)));
                    }
                    */

                    // Alternatif: Manual insert to database jika tidak pakai class Notification
                    $message = "Tindak lanjut untuk temuan pada audit " . ($temuan->audit->judul ?? '-') . " akan jatuh tempo dalam " . ($daysLeft == 0 ? "hari ini" : ceil($daysLeft) . " hari") . ".";
                    
                    if ($auditee) {
                        $this->createNotification($auditee, $temuan, $message);
                    }
                    if ($auditor && $auditor->id !== ($auditee->id ?? 0)) {
                        $this->createNotification($auditor, $temuan, $message);
                    }
                }

                $count++;
            }
        }

        if ($isDryRun) {
            $this->info("[DRY RUN] $count notifikasi akan dikirim.");
        } else {
            $this->info("$count notifikasi berhasil dikirim.");
        }
    }

    private function createNotification(User $user, Temuan $temuan, string $message)
    {
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\DeadlineTindakLanjutNotification',
            'data' => [
                'temuan_id' => $temuan->id,
                'audit_id' => $temuan->audit_id,
                'message' => $message,
                'link' => route('temuan.show', $temuan->id),
                'icon' => 'bi-clock-history',
                'color' => 'warning'
            ],
            'read_at' => null,
        ]);
    }
}
