<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $checkout;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $checkout)
    {
        $this->order = $order;
        $this->checkout = $checkout;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order was successfully created')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Thank you for making a purchase.')
            ->line('Order ID: ' . $this->order->unique_order_id)
            ->line('Total Payments: Rp ' . number_format($this->order->amount))
            ->action('View Order Details', url('/'))
            ->line('We will inform you of the delivery status of your order shortly.');
    }


    public function toDatabase(object $notifiable): array
    {
        $order = $this->order;

        return [
            'message' => "Your order was successfully created. {$order->unique_order_id}"
        ];
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
