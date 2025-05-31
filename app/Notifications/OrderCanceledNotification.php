<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCanceledNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $user;

    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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

    public function toDatabase($notifiable)
    {
        if ($notifiable->role === 'admin') {
            return [
                'order_id' => $this->order->id,
                'unique_order_id' => $this->order->unique_order_id,
                'message' => $this->order->unique_order_id . ' was canceled by ' . $this->user->name,
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
            ];
        }

        return [
            'order_id' => $this->order->id,
            'unique_order_id' => $this->order->unique_order_id,
            'message' => $this->order->unique_order_id . ' has been canceled.',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
        ];
    }
}
