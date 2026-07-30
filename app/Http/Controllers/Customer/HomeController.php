<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\CartServiceInterface;
use App\Contracts\MenuServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;

class HomeController extends Controller
{
    public function __construct(
        private RestaurantServiceInterface $restaurantService,
        private MenuServiceInterface $menuService,
        private CartServiceInterface $cartService
    ) {}
    public function index()
    {
        $featuredRestaurants = $this->restaurantService->getFeaturedRestaurants();

        $restaurants = $this->restaurantService->getAllRestaurants([
            'status'   => 'active',
            'city'     => request()->city,
            'search'   => request()->search,
            'per_page' => 12,
        ]);

        $cities = $this->restaurantService->getRestaurantCities();

        return view('customer.home', compact('featuredRestaurants', 'restaurants', 'cities'));
    }

    public function show(Restaurant $restaurant)
    {
        // Only show active restaurants to customers
        if ($restaurant->status !== 'active') {
            abort(404);
        }
        $restaurant->load(['reviews'], function ($q) {
            $q->with('user:id,name,avatar')->latest()->limit(10);
        });

        $restaurant->loadCount('reviews');
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);
        $cart = $this->cartService->getCart();
        dd($cart);
        dd($restaurant->toArray());
    }
}
