<?php

namespace App\Services;

use App\Repositories\RestaurantRepository;

class RestaurantService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private RestaurantRepository $restaurantRepository) {}

    public function getAllRestaurants(array $filters): mixed
    {
        return $this->restaurantRepository->getAll($filters);
    }

    public function getRestaurantByOwner(int $ownerId): mixed
    {
        return $this->restaurantRepository->findById($ownerId);
    }
}
