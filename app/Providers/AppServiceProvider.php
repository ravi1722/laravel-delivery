<?php

namespace App\Providers;

use App\Contracts\AddressServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\MenuServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Contracts\StorageServiceInterface;
use App\Models\Restaurant;
use App\Observers\RestaurantObserver;
use App\Services\AddressService;
use App\Services\CartService;
use App\Services\MenuService;
use App\Services\OrderService;
use App\Services\RestaurantService;
use App\Services\StorageService;
use App\View\Composers\CustomerSidebarComposer;
use App\View\Composers\RestaurantOwnerSidebarComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StorageServiceInterface::class, StorageService::class);
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
        // Prevent N+1 in development
        Model::preventLazyLoading(!app()->isProduction()); //N+1 query problem-ஐ கண்டுபிடிக்க உதவும்
        // Prevent silently discarding attributes
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction()); //இது Mass Assignment / Unknown Attributes தொடர்பான mistakes-ஐ கண்டுபிடிக்க உதவும்.
        //app()->isProduction() - Production-ல் unexpected exception காரணமாக existing application flow பாதிக்கப்படக்கூடாது என்பதால்
    

        // Horizon authentication
        Horizon::auth(function ($request) {
            // Local — allow all for easy development
            if (app()->environment('local')) {
                return true;
            }

            // Production — admin only
            return $request->user()?->isAdmin() ?? false;
        });
        Restaurant::observe(RestaurantObserver::class);
        View::composer("partials.sidebar-customer", CustomerSidebarComposer::class);    //partials.sidebar-customer load ஆகும் போதெல்லாம் service automatically call ஆகும்
        View::composer("partials.sidebar-restaurant_owner", RestaurantOwnerSidebarComposer::class);

        // ── API Rate Limiters ──────────────────────────
        // Guest — very limited
        RateLimiter::for('api.guest', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Authenticated customer — standard
        RateLimiter::for('api.customer', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?? $request->ip());
        });

        // Restaurant owner — higher limit
        RateLimiter::for('api.restaurant', function (Request $request) {
            return Limit::perMinute(300)->by($request->user()?->id ?? $request->ip());
        });

        // Admin — unlimited practically
        RateLimiter::for('api.admin', function (Request $request) {
            return Limit::perMinute(1000)->by($request->user()?->id ?? $request->ip());
        });

        // Dynamic limiter based on user role
        RateLimiter::for('api', function (Request $request) {
            if (!$request->user()) {
                return Limit::perMinute(15)->by($request->ip());
            }

            return match ($request->user()->role) {
                'admin'            => Limit::perMinute(1000)->by($request->user()->id),
                'restaurant_owner' => Limit::perMinute(300)->by($request->user()->id),
                default            => Limit::perMinute(120)->by($request->user()->id),
            };
        });
    }
}
