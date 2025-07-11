<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $oldOrderStatus;
    protected $oldPaymentStatus;

    public function __construct(Order $order, ?string $oldOrderStatus = null, ?string $oldPaymentStatus = null)
    {
        $this->order = $order;
        $this->oldOrderStatus = $oldOrderStatus;
        $this->oldPaymentStatus = $oldPaymentStatus;
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
        $isAdmin = $notifiable->role === 'admin';

        $changes = [];

        if ($this->oldOrderStatus !== null && $this->oldOrderStatus !== $this->order->order_status) {
            $changes[] = "Order status changed from '{$this->oldOrderStatus}' to '{$this->order->order_status}'";
        }

        if ($this->oldPaymentStatus !== null && $this->oldPaymentStatus !== $this->order->payment_status) {
            $changes[] = "Payment status changed from '{$this->oldPaymentStatus}' to '{$this->order->payment_status}'";
        }

        if (empty($changes)) {
            $message = "Status order #{$this->order->unique_order_id} updated with no changes detected.";
        } else {
            $message = implode(' and ', $changes);
            $message = "Status update for order #{$this->order->unique_order_id}: " . $message . '.';
        }

        if ($isAdmin) {
            return [
                'title' => 'Order Status Updated',
                'message' => $message,
                'order_id' => $this->order->id,
                'url' => route('orders.index'),
            ];
        } else {
            return [
                'title' => 'Your order status has been updated',
                'message' => $message,
                'order_id' => $this->order->id,
                'url' => route('orders.history', $this->order->id),
            ];
        }
    }
}
