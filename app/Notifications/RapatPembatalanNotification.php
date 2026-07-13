<?php

namespace App\Notifications;

use App\Models\Rapat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RapatPembatalanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Rapat $rapat) {}

    public function via($notifiable): array { return ['database', 'mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Rapat Dibatalkan: {$this->rapat->judul}")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Rapat berikut telah **dibatalkan**:")
            ->line("**{$this->rapat->judul}**")
            ->line("Tanggal: " . $this->rapat->tanggal->format('d M Y'))
            ->line("Alasan: " . ($this->rapat->alasan_pembatalan ?? '-'))
            ->line('Mohon hubungi penyelenggara untuk informasi lebih lanjut.')
            ->salutation('Terima kasih, Tim SPMI');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'     => 'rapat_dibatalkan',
            'rapat_id' => $this->rapat->id,
            'judul'    => $this->rapat->judul,
            'tanggal'  => $this->rapat->tanggal->format('d M Y'),
            'alasan'   => $this->rapat->alasan_pembatalan,
            'url'      => route('rapat.show', $this->rapat->id),
            'message'  => "Rapat \"{$this->rapat->judul}\" telah dibatalkan.",
        ];
    }
}
