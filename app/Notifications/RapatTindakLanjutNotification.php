<?php

namespace App\Notifications;

use App\Models\Rapat;
use App\Models\RapatTindakLanjut;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RapatTindakLanjutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected RapatTindakLanjut $tindakLanjut,
        protected string $type = 'assigned' // assigned | reminder | overdue
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $rapat = $this->tindakLanjut->rapat;
        $url   = route('rapat.show', $rapat->id);

        $messages = [
            'assigned' => "Anda ditugaskan sebagai PIC tindak lanjut: \"{$this->tindakLanjut->deskripsi}\" — deadline {$this->tindakLanjut->deadline->format('d M Y')}.",
            'reminder' => "Pengingat: Tindak lanjut \"{$this->tindakLanjut->deskripsi}\" akan jatuh tempo pada {$this->tindakLanjut->deadline->format('d M Y')}.",
            'overdue'  => "TERLAMBAT: Tindak lanjut \"{$this->tindakLanjut->deskripsi}\" sudah melewati deadline {$this->tindakLanjut->deadline->format('d M Y')}.",
        ];

        return [
            'type'            => 'tindak_lanjut_rapat',
            'sub_type'        => $this->type,
            'tindak_lanjut_id'=> $this->tindakLanjut->id,
            'rapat_id'        => $rapat->id,
            'rapat_judul'     => $rapat->judul,
            'deskripsi'       => $this->tindakLanjut->deskripsi,
            'deadline'        => $this->tindakLanjut->deadline->format('d M Y'),
            'url'             => $url,
            'message'         => $messages[$this->type] ?? $messages['assigned'],
        ];
    }
}
