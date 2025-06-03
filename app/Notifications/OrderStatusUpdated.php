<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $previousStatus
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('customer.orders.show', $this->order->id);
        $statusMessage = $this->getStatusMessage();

        return (new MailMessage)
            ->subject('Order Status Updated')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your order #' . $this->order->id . ' status has been updated.')
            ->line($statusMessage)
            ->action('View Order Details', $url);
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Your order #' . $this->order->id . ' status has been updated to ' . $this->order->status,
            'previous_status' => $this->previousStatus,
            'current_status' => $this->order->status,
            'url' => route('customer.orders.show', $this->order->id),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'message' => 'Your order #' . $this->order->id . ' status has been updated to ' . $this->order->status,
            'previous_status' => $this->previousStatus,
            'current_status' => $this->order->status,
            'url' => route('customer.orders.show', $this->order->id),
        ]);
    }

    private function getStatusMessage(): string
    {
        return match($this->order->status) {
            'processing' => 'Your order is now being processed and will be prepared for delivery.',
            'shipped' => 'Your order has been shipped and is on its way to you.',
            'delivered' => 'Your order has been delivered successfully.',
            'cancelled' => 'Your order has been cancelled.',
            default => 'Your order status has been updated from ' . $this->previousStatus . ' to ' . $this->order->status,
        };
    }
} 