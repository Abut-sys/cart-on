<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $checkout;
    protected $user;


    /**
     * Create a new notification instance.
     */
    public function __construct($order, $checkout, $user)
    {
        $this->order = $order;
        $this->checkout = $checkout;
        $this->user = $user;
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
            ->subject('New Incoming Orders')
            ->line('New orders from '  . $this->user->name)
            ->line('Order ID: ' . $this->order->unique_order_id)
            ->action('View Order', url('/orders'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'New order created with ID: ' . $this->order->unique_order_id,
            'order_id' => $this->order->id,
            'user' => $this->user->name,
            'url' => '/orders',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
