<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\MenuServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuCategoryResource;
use App\Http\Resources\Api\V1\MenuItemResource;
use App\Http\Resources\Api\V1\RestaurantResource;
use App\Models\Restaurant;
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

    // GET /api/v1/restaurants/featured
    public function featured()
    {
        $restaurants = $this->restaurantService->getFeaturedRestaurants();

        return $this->success(
            RestaurantResource::collection($restaurants),
            'Featured restaurants retrieved.'
        );
    }

    // GET /api/v1/restaurants/{slug}
    public function show(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $restaurant->load(['menuCategories' => function ($q) {
            $q->active()->with(['items' => function ($q) {
                $q->available()->with(['variants', 'addons']);
            }]);
        }]);

        $restaurant->loadCount(['reviews', 'orders']);

        return $this->success(
            new RestaurantResource($restaurant),
            'Restaurant retrieved successfully.'
        );
    }

    // GET /api/v1/restaurants/{id}/menu
    public function menu(Restaurant $restaurant)
    {
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);

        // Load items for each category
        $categories->load(['items' => function ($q) {
            $q->available()->with(['variants', 'addons']);
        }]);

        return $this->success(
            MenuCategoryResource::collection($categories),
            'Menu retrieved successfully.'
        );
    }

    // GET /api/v1/restaurants/{id}/menu/search
    public function searchMenu(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $items = $this->menuService->getItemsByRestaurant(
            $restaurant->id,
            ['search' => $request->q, 'is_available' => true]
        );

        return $this->success(
            MenuItemResource::collection($items),
            'Search results retrieved.'
        );
    }
}
