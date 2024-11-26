<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;

class VoucherStatusChangedNotification extends Notification
{
    use Queueable;

    protected $voucher;

    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database']; // Menggunakan broadcast dan database
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Status voucher {$this->voucher->code} : telah berubah menjadi {$this->voucher->status}.",
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Status voucher {$this->voucher->code} : telah berubah menjadi {$this->voucher->status}.",
        ];
    }
}
