<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class TestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::findOrFail($this->orderId);
        Mail::to($order->user->email)
            ->send(new OrderConfirmationMail($order));
    }
}
