<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(private RestaurantServiceInterface $restaurantService) {}

    public function index() {
        $restaurant = $this->restaurantService->getRestaurantByOwner(Auth::user()->id);
        if (!$restaurant) {
            // return redirect()->route('restaurant.profile.create')
            //                  ->with('info', 'Please set up your restaurant profile first.');
        }

        // $stats = cacheRemember("",$restaurant.$restaurant->id, 300, function() {

        // });
    }
}
