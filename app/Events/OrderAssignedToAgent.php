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

class OrderAssignedToAgent
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
            new PrivateChannel('agent.' . $this->order->delivery_agent_id),
        ];
    }

    // public function broadcastWith(): array
    // {
    //     return [
    //         'order_id'           => $this->order->id,
    //         'order_number'       => $this->order->order_number,
    //         'restaurant_name'    => $this->order->restaurant->name,
    //         'restaurant_address' => $this->order->restaurant->address,
    //         'delivery_address'   => $this->order->address->address_line1 . ', ' . $this->order->address->city,
    //         'total_amount'       => $this->order->total_amount,
    //     ];
    // }

    // public function broadcastAs(): string
    // {
    //     return 'order.assigned';
    // }
}
