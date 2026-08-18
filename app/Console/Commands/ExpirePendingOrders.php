<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirePendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-cancel orders that have been in placed status for too long';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cancel orders that are still 'placed' after 15 minutes
        // (Restaurant hasn't confirmed — probably offline)
        $expiredOrders = Order::where('status', 'placed')->where('created_at', '<', now()->subMinutes(15))->get();
        $count = 0;
        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'cancelled']);
            // Notify customer
            $order->user->notify(
                new OrderStatusNotification($order, 'placed')
            );
            $order->statusHistories()->create([
                'status' => 'cancelled',
                'note'   => 'Auto-cancelled: Restaurant did not confirm within 15 minutes.',
            ]);

            $count++;
        }
        $this->info("Expired {$count} pending orders.");
        Log::info("Scheduled: Expired {$count} pending orders.");
    }
}
