<?php

namespace App\Providers;

use App\Contracts\AddressServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\MenuServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Models\Restaurant;
use App\Observers\RestaurantObserver;
use App\Services\AddressService;
use App\Services\CartService;
use App\Services\MenuService;
use App\Services\OrderService;
use App\Services\RestaurantService;
use App\View\Composers\CustomerSidebarComposer;
use App\View\Composers\RestaurantOwnerSidebarComposer;
use Illuminate\Support\Facades\View;
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
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(AddressServiceInterface::class, AddressService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Restaurant::observe(RestaurantObserver::class);
        View::composer("partials.sidebar-customer", CustomerSidebarComposer::class);    //partials.sidebar-customer load ஆகும் போதெல்லாம் service automatically call ஆகும்
        View::composer("partials.sidebar-restaurant_owner", RestaurantOwnerSidebarComposer::class);
    }
}
