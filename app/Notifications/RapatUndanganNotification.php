<?php

namespace App\Notifications;

use App\Models\Rapat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RapatUndanganNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Rapat $rapat) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('rapat.show', $this->rapat->id);

        return (new MailMessage)
            ->subject("Undangan Rapat: {$this->rapat->judul}")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Anda diundang untuk menghadiri rapat berikut:")
            ->line("**{$this->rapat->judul}**")
            ->line("Jenis  : " . Rapat::jenisOptions()[$this->rapat->jenis])
            ->line("Tanggal: " . $this->rapat->tanggal->format('d M Y'))
            ->line("Waktu  : " . substr($this->rapat->waktu_mulai, 0, 5) . ' – ' . substr($this->rapat->waktu_selesai, 0, 5))
            ->line("Tempat : {$this->rapat->tempat}")
            ->action('Lihat Detail Rapat', $url)
            ->line('Mohon konfirmasi kehadiran Anda melalui sistem.')
            ->salutation('Terima kasih, Tim SPMI');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'      => 'undangan_rapat',
            'rapat_id'  => $this->rapat->id,
            'judul'     => $this->rapat->judul,
            'jenis'     => $this->rapat->jenis,
            'tanggal'   => $this->rapat->tanggal->format('d M Y'),
            'waktu'     => substr($this->rapat->waktu_mulai, 0, 5) . ' – ' . substr($this->rapat->waktu_selesai, 0, 5),
            'tempat'    => $this->rapat->tempat,
            'url'       => route('rapat.show', $this->rapat->id),
            'message'   => "Anda diundang ke rapat: {$this->rapat->judul} pada {$this->rapat->tanggal->format('d M Y')}.",
        ];
    }
}
