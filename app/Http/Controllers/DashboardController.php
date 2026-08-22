<?php

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'restaurant_owner' => redirect()->route('restaurant.dashboard'),
            'delivery_agent' => redirect()->route('agent.dashboard'),
            default => view('customer.dashboard')
        };
    }
}
