<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Order $order) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders' . $this->order->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'status'       => $this->order->status,
            'status_label' => ucfirst(str_replace('_', ' ', $this->order->status)),
            'message'      => $this->getStatusMessage(),
            'updated_at'   => $this->order->updated_at->toIso8601String(),
        ];
    }

    public function broadcastAs()
    {
        return 'order.status.updated';
    }
    private function getStatusMessage(): string
    {
        return match ($this->order->status) {
            'confirmed' => "Your order #{$this->order->order_number} has been confirmed!",
            'preparing' => "Restaurant is preparing your order.",
            'ready'     => "Your order is ready for pickup!",
            'picked_up' => "Your order is on the way!",
            'delivered' => "Your order has been delivered. Enjoy your meal! 🎉",
            'cancelled' => "Your order #{$this->order->order_number} has been cancelled.",
            default     => "Order status updated.",
        };
    }
}
