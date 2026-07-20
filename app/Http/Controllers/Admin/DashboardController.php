<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RestaurantStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {

        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'total_restaurants' => Restaurant::count(),
                'pending_restaurants' => Restaurant::where('status', RestaurantStatus::Pending)->count(),
                'active_restaurants' => Restaurant::where('status', RestaurantStatus::Active)->count(),
                'total_users' => User::where('role', 'customer')->count(),
                'total_orders' => Order::where('payment_status', 'paid')->count(),
                'today_orders' => Order::where('payment_status', 'paid')->whereDate('created_at', today())->count(),
                'today_revenue' => Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount'),
                'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            ];
        });

        $pendingRestaurant = Restaurant::with('owner:id,name,email')->latest()->where('status', RestaurantStatus::Pending)->limit(5)->get();

        $recentOrders = Order::with(['user:id,name', 'restaurant:id,name'])->latest()->limit(10)->get();

        $statusColors = [
            'placed'    => 'primary',
            'confirmed' => 'info',
            'preparing' => 'warning',
            'ready'     => 'secondary',
            'picked_up' => 'dark',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        return view('admin.dashboard', compact('stats', 'pendingRestaurant', 'recentOrders', 'statusColors'));
    }
}
