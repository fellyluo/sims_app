<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadReceived extends Notification
{
    use Queueable;

    public function __construct(public Lead $lead)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tier = $this->lead->tier_diminati
            ? ucfirst($this->lead->tier_diminati)
            : 'Belum memilih';

        return (new MailMessage)
            ->subject('Permintaan demo SIMS dari '.$this->lead->sekolah)
            ->greeting('Lead baru dari situs SIMS')
            ->line('Nama: '.$this->lead->nama)
            ->line('Sekolah: '.$this->lead->sekolah)
            ->line('Jabatan: '.($this->lead->jabatan ?: '-'))
            ->line('Email: '.$this->lead->email)
            ->line('WhatsApp: '.($this->lead->no_hp ?: '-'))
            ->line('Perkiraan siswa: '.($this->lead->perkiraan_siswa ?: '-'))
            ->line('Paket diminati: '.$tier)
            ->line('Sumber: '.$this->lead->sumber)
            ->line('Pesan: '.($this->lead->pesan ?: '-'))
            ->action('Buka halaman kontak', url('/kontak'));
    }
}
