<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutOfStockNotification extends Notification
{
    use Queueable;

    protected $subVariant;

    /**
     * Create a new notification instance.
     */
    public function __construct($subVariant)
    {
        $this->subVariant = $subVariant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Out of Stock',
            'message' => "Stock for products {$this->subVariant->product->name} (Color: {$this->subVariant->color}, Size: {$this->subVariant->size}) was exhausted.",
            'product_id' => $this->subVariant->product->id,

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
