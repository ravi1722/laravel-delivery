<?php

namespace App\Services;

use App\Contracts\RestaurantServiceInterface;
use App\Repositories\RestaurantRepository;

class RestaurantService implements RestaurantServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(private RestaurantRepository $restaurantRepository) {}

    public function getAllRestaurants(array $filters = []): mixed
    {
        return $this->restaurantRepository->getAll($filters);
    }

    // public function getRestaurantById(int $id): mixed
    // {
    //     //
    // }

    public function getRestaurantByOwner(int $ownerId): mixed
    {
        return $this->restaurantRepository->findById($ownerId);
    }

    // public function createRestaurant(array $data): mixed {}
    // public function updateRestaurant(int $id, array $data): mixed {}
    // public function deleteRestaurant(int $id): bool {}
    // public function toggleStatus(int $id): mixed {}
    // public function approveRestaurant(int $id): mixed {}
    // public function getFeaturedRestaurants(): mixed {}
}
