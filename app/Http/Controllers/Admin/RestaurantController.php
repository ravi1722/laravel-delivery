<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(private RestaurantServiceInterface $restaurantService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurants = $this->restaurantService->getAllRestaurants(request()->all());
        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $owners = User::where('role', 'restaurant_owner')->get();
        return view('admin.restaurants.create', compact('owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRestaurantRequest $request)
    {
        $request->validate(['owner_id' => 'required|exists:users,id']);
        $data = $request->validated();
        $data['owner_id'] = $request->owner_id;

        $this->restaurantService->createRestaurant($data);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load([
            'owner:id,name,email,phone',
            'menuCategories.items',
            'orders' => fn($q) => $q->latest()->limit(10),
        ]);

        $restaurant->loadCount(['orders', 'reviews', 'menuItems']);

        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $this->restaurantService->updateRestaurant($restaurant->id, $request->validated());

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->restaurantService->deleteRestaurant($restaurant->id);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant deleted successfully!');
    }

    public function approve(Restaurant $restaurant)
    {
        $this->restaurantService->approveRestaurant($restaurant->id);

        return back()->with('success', "Restaurant '{$restaurant->name}' has been approved!");
    }

    public function toggleStatus(Restaurant $restaurant)
    {
        $this->restaurantService->toggleStatus($restaurant->id);

        return back()->with('success', 'Restaurant status updated!');
    }
}
