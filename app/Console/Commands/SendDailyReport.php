<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\DailyReportNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily summary report to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = now()->subDay()->toDateString();
        $stats = [
            'date'             => $yesterday,
            'total_orders'     => Order::whereDate('created_at', $yesterday)->count(),
            'delivered_orders' => Order::whereDate('created_at', $yesterday)->where('status', 'delivered')->count(),
            'cancelled_orders' => Order::whereDate('created_at', $yesterday)->where('status', 'cancelled')->count(),
            'total_revenue'    => Order::whereDate('created_at', $yesterday)->where('payment_status', 'paid')->sum('total_amount'),
            'new_users'        => User::whereDate('created_at', $yesterday)->where('role', 'customer')->count(),
            'active_restaurants' => Restaurant::where('status', 'active')->count(),
        ];
        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new DailyReportNotification($stats));
        }
        $this->info("Daily report sent to {$admins->count()} admin(s).");
        Log::info("Scheduled: Daily report sent.", $stats);
    }
}
