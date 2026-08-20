<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\MenuServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RestaurantResource;
use Illuminate\Http\Request;

class RestaurantController extends BaseApiController
{
    public function __construct(
        private RestaurantServiceInterface $restaurantService,
        private MenuServiceInterface $menuService
    ) {}

    // GET /api/v1/restaurants
    public function index(Request $request)
    {
        $restaurants = $this->restaurantService->getAllRestaurants([
            'status'   => 'active',
            'city'     => $request->city,
            'search'   => $request->search,
            'per_page' => $request->per_page ?? 12,
        ]);

        return $this->paginated(
            RestaurantResource::collection($restaurants)->resource,
            'Restaurants retrieved successfully.'
        );
    }
}
