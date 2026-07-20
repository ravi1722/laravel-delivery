<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private RestaurantServiceInterface $restaurantService) {}

    public function index() {
        $restaurant = $this->restaurantService->getRestaurantByOwner(Auth::user()->id);

    }
}
