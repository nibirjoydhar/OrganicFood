<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('customer.orders.show', $this->order->id);

        return (new MailMessage)
            ->subject('Order Placed Successfully')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your order.')
            ->line('Your order #' . $this->order->id . ' has been placed successfully.')
            ->line('Total Amount: ৳' . number_format($this->order->total, 2))
            ->action('View Order Details', $url)
            ->line('We will notify you once your order status is updated.');
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Your order #' . $this->order->id . ' has been placed successfully.',
            'url' => route('customer.orders.show', $this->order->id),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'message' => 'Your order #' . $this->order->id . ' has been placed successfully.',
            'url' => route('customer.orders.show', $this->order->id),
        ]);
    }
} 