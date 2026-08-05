<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
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

        Log::info('Order confirmation notification sent', [
            'order_id'    => $order->id,
            'order_number' => $order->order_number,
            'user_email'  => $order->user->email,
            'total'       => $order->total_amount,
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
