<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessOrderCancellation
{
    public string $queue = 'default';
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
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order->load(['user', 'restaurant']);

        // Restore stock if needed
        // Process refund if payment was made
        Log::info("Order cancellation processed", [
            'order_id' => $order->id,
            'reason'   => $event->reason,
        ]);
    }
}
