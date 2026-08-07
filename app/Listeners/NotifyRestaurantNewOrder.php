<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotifyRestaurantNewOrder
{
    public string $queue = 'notifications';
    public int    $tries = 3;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load(['restaurant.owner']);
        Log::info("Restaurant notified about new order", [
            'order_id'       => $order->id,
            'restaurant'     => $order->restaurant->name,
            'owner_email'    => $order->restaurant->owner->email,
        ]);
    }

    public function failed(OrderPlaced $event, Throwable $e): void
    {
        Log::error("", [
            'order_id'       => $event->order->id,
            'message' => $e->getMessage(),
        ]);
    }
}
