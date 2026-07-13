<?php

namespace App\Console\Commands;

use App\Models\RapatTindakLanjut;
use App\Notifications\RapatTindakLanjutNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRapatDeadlineReminders extends Command
{
    protected $signature   = 'spmi:rapat-reminders';
    protected $description = 'Kirim pengingat deadline tindak lanjut rapat';

    public function handle(): void
    {
        $this->info('Memulai pengecekan deadline tindak lanjut rapat...');

        $items = RapatTindakLanjut::with(['pic', 'rapat.creator'])
            ->whereIn('status', [RapatTindakLanjut::STATUS_BELUM_MULAI, RapatTindakLanjut::STATUS_DALAM_PROSES])
            ->whereNotNull('deadline')
            ->get();

        $count = 0;

        foreach ($items as $tl) {
            $deadline      = Carbon::parse($tl->deadline);
            $today         = Carbon::now()->startOfDay();
            $daysRemaining = $today->diffInDays($deadline, false); // negative = overdue

            // H-3: pengingat pertama
            if ($daysRemaining == 3) {
                $this->sendSafe($tl->pic, $tl, 'reminder');
                $count++;
            }

            // H-0: pengingat kedua (hari deadline)
            if ($daysRemaining == 0) {
                $this->sendSafe($tl->pic, $tl, 'reminder');
                $count++;
            }

            // H+1 ke atas: notifikasi keterlambatan ke PIC & penyelenggara
            if ($daysRemaining == -1) {
                $this->sendSafe($tl->pic, $tl, 'overdue');
                if ($tl->rapat->creator && $tl->rapat->creator->id !== $tl->pic_id) {
                    $this->sendSafe($tl->rapat->creator, $tl, 'overdue');
                }
                $count++;
            }
        }

        $this->info("Selesai! {$count} notifikasi telah dikirim.");
    }

    private function sendSafe($notifiable, RapatTindakLanjut $tl, string $type): void
    {
        try {
            $notifiable->notify(new RapatTindakLanjutNotification($tl, $type));
        } catch (\Exception $e) {
            \Log::warning("Gagal kirim reminder rapat TL {$tl->id}: " . $e->getMessage());
        }
    }
}
