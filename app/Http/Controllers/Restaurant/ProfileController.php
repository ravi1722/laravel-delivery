<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private RestaurantServiceInterface $restaurant_service) {}

    public function create()
    {
        if (Auth::user()->restaurant) {
            return redirect()->route('restaurant.dashboard');
        }
        return view('restaurant.profile.create');
    }

    public function store(StoreRestaurantRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = Auth::user()->id;

        $this->restaurant_service->createRestaurant($data);

        return redirect()->route('restaurant.dashboard')
            ->with('success', 'Restaurant profile created! Pending admin approval.');
    }

    public function edit()
    {
        $restaurant = Auth::user()->restaurant;
        return view('restaurant.profile.edit', compact('restaurant'));
    }

    public function update(UpdateRestaurantRequest $request) {
        $restaurant = Auth::user()->restaurant;

        $this->authorize('update', $restaurant);

        $this->restaurant_service->updateRestaurant($restaurant->id, $request->validated());
        return back()->with('success', 'Restaurant profile updated successfully!');
    }

    public function getCityState(int $pincode)
    {
        if (!preg_match('/^[1-9][0-9]{5}$/', $pincode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid pincode.'
            ]);
        }
        $address = getCityByPincode($pincode);
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Pincode'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }
}
