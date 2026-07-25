<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(private RestaurantServiceInterface $restaurantService) {}

    public function index()
    {
        $restaurant = $this->restaurantService->getRestaurantByOwner(Auth::user()->id);
        if (!$restaurant) {
            return redirect()->route('restaurant.profile.create')
                ->with('info', 'Please set up your restaurant profile first.');
        }

        // $stats = cacheRemember("",$restaurant.$restaurant->id, 300, function() {

        // });

        $stats = [
            'today_orders' => Order::forRestaurant($restaurant->id)->whereDate('created_at', today())->count(),
            'today_revenue' => Order::forRestaurant($restaurant->id)->whereDate('created_at', today())->sum('total_amount'),
            'pending_orders' => Order::forRestaurant($restaurant->id)->whereIn('status', ['placed', 'confirmed', 'preparing'])->count(),
            'total_orders' => Order::forRestaurant($restaurant->id)->count(),
            'total_revenue' => Order::forRestaurant($restaurant->id)->where('payment_status', 'paid')->sum('total_amount'),
            'total_menu_items' => $restaurant->menuItems()->count()
        ];

        $recentOrders = Order::with('user:id,name,phone')->forRestaurant($restaurant->id)->latest()->limit(10)->get();

        return view('restaurant.dashboard', compact('recentOrders', 'restaurant', 'stats'));
    }
}
