<?php

namespace App\Providers;

use App\Contracts\MenuServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Models\Restaurant;
use App\Observers\RestaurantObserver;
use App\Services\MenuService;
use App\Services\RestaurantService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RestaurantServiceInterface::class, RestaurantService::class);
        $this->app->bind(MenuServiceInterface::class, MenuService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Restaurant::observe(RestaurantObserver::class);
    }
}
