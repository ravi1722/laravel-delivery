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

    public function getAll(array $filters = [])
    {
        $cacheKey = 'restaurant.list' . md5(serialize($filters));

        return Cache::tags([self::CACHE_TAG])->remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $query = Restaurant::with(["owner:id,name,email,phone"])->withCount(['orders', 'reviews']);

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['city'])) {
                $query->inCity($filters['city']);
            }

            if (!empty($filters['is_featured'])) {
                $query->featured();
            }

            if (!empty($filters['search'])) {
                $query->where(function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('cuisine_type', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('city', 'like', '%' . $filters['search'] . '%');
                });
            }

            return $query->latest()->paginate($filters['paginate'] ?? 15);
        });
    }

    public function findById(int $id) {
        return Cache::tags([self::CACHE_TAG])->remember("restaurants".$id, self::CACHE_TTL, function () use ($id) {
            return Restaurant::with(["owner:id,name,email,phone","menuCategories.items"])->withCount(['orders', 'reviews'])->findOrFail($id);
        });
    }

    public function clearCache(): void {
        Cache::tags([self::CACHE_TAG])->flush();
    }
}
