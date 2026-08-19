<?php

namespace App\Listeners;

use App\Events\NewOrderReceived;
use App\Events\OrderPlaced;
use App\Events\OrderStatusUpdated;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendOrderConfirmationNotification
{
    public string $queue = "notifications";
    public int $tries = 3;
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
        $order = $event->order->load(['user', 'restaurant', 'orderItems']);
        // 1. Notify customer via mail + database
        // $order->user->notify(new OrderPlacedNotification($order));
        // 2. Notify restaurant owner via mail + database
        // $order->restaurant->owner->notify(new NewOrderNotification($order));

        // 3. Broadcast to customer's private channel — real-time
        // OrderStatusUpdated::dispatch($order);

        // 4. Broadcast to restaurant's private channel — real-time
        NewOrderReceived::dispatch($order);

        Log::info("Order notifications sent", [
            'order_id' => $order->id,
        ]);
    }

    public function failed(OrderPlaced $event, Throwable $e): void
    {
        Log::error('Failed to send order confirmation', [
            'order_id' => $event->order->id,
            'error'    => $e->getMessage(),
        ]);
    }
}
