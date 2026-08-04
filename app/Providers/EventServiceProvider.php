<?php

namespace App\Providers;

use App\Events\OrderCancelled;
use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Listeners\NotifyRestaurantNewOrder;
use App\Listeners\ProcessOrderCancellation;
use App\Listeners\SendOrderConfirmationNotification;
use App\Listeners\UpdateOrderStatusHistory;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [SendOrderConfirmationNotification::class, NotifyRestaurantNewOrder::class],
        OrderStatusChanged::class => [UpdateOrderStatusHistory::class],
        OrderCancelled::class => [ProcessOrderCancellation::class],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
