<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Events\OrderStatusUpdated;
use App\Models\OrderStatusHistory;
use App\Notifications\OrderStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class UpdateOrderStatusHistory
{
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
    public function handle(OrderStatusChanged $event): void
    {
        OrderStatusHistory::create([
            'order_id'   => $event->order->id,
            'status'     => $event->newStatus,
            'note'       => "Status changed from {$event->previousStatus} to {$event->newStatus}",
            'changed_by' => Auth::user()->id,
        ]);

        // 2. Notify customer via database notification
        $event->order->user->notify(
            new OrderStatusNotification($event->order, $event->previousStatus)
        );

        // 3. Broadcast real-time update to customer
        // OrderStatusUpdated::dispatch($event->order);
    }
}
