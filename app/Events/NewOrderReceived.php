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

class NewOrderReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Order $order)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('restaurant.' . $this->order->restaurant_id),
        ];
    }

    // public function broadcastWith(): array
    // {
    //     return [
    //         'order_id'      => $this->order->id,
    //         'order_number'  => $this->order->order_number,
    //         'total_amount'  => $this->order->total_amount,
    //         'items_count'   => $this->order->items()->count(),
    //         'payment_method' => $this->order->payment_method,
    //         'customer_name' => $this->order->user->name,
    //         'placed_at'     => $this->order->created_at->format('h:i A'),
    //     ];
    // }

    // public function broadcastAs(): string
    // {
    //     return 'new.order.received';
    // }
}
