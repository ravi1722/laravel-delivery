<?php

namespace App\Repositories;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

class RestaurantRepository
{
    /**
     * Create a new class instance.
     */
    const CACHE_TTL = 3600;
    const CACHE_TAG = "restaurants";

    public function getAll(array $filters = []) {
        $cacheKey = 'restaurant.list'. md5(serialize($filters));

        return Cache::tags([self::CACHE_TAG])->remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $query = Restaurant::with(["owner:id,name,email,phone"])->withCount(['orders','reviews']);

            return $query;
        });
    }
}
