<?php

namespace App\Notifications;

use App\Models\Rapat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RapatPerubahanJadwalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Rapat $rapat) {}

    public function via($notifiable): array { return ['database', 'mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Perubahan Jadwal Rapat: {$this->rapat->judul}")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Jadwal rapat berikut telah **diubah**:")
            ->line("**{$this->rapat->judul}**")
            ->line("Tanggal Baru: " . $this->rapat->tanggal->format('d M Y'))
            ->line("Waktu Baru  : " . substr($this->rapat->waktu_mulai, 0, 5) . ' – ' . substr($this->rapat->waktu_selesai, 0, 5))
            ->line("Tempat      : {$this->rapat->tempat}")
            ->action('Lihat Detail Rapat', route('rapat.show', $this->rapat->id))
            ->salutation('Terima kasih, Tim SPMI');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'rapat_jadwal_berubah',
            'rapat_id'=> $this->rapat->id,
            'judul'   => $this->rapat->judul,
            'tanggal' => $this->rapat->tanggal->format('d M Y'),
            'waktu'   => substr($this->rapat->waktu_mulai, 0, 5) . ' – ' . substr($this->rapat->waktu_selesai, 0, 5),
            'url'     => route('rapat.show', $this->rapat->id),
            'message' => "Jadwal rapat \"{$this->rapat->judul}\" telah diubah ke {$this->rapat->tanggal->format('d M Y')}.",
        ];
    }
}
