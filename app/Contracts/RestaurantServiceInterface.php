<?php

namespace App\Contracts;

interface RestaurantServiceInterface
{
    public function getAllRestaurants(array $filters = []): mixed;
    // public function getRestaurantById(int $id): mixed;
    public function getRestaurantByOwner(int $ownerId): mixed;
    // public function createRestaurant(array $data): mixed;
    // public function updateRestaurant(int $id, array $data): mixed;
    // public function deleteRestaurant(int $id): bool;
    // public function toggleStatus(int $id): mixed;
    // public function approveRestaurant(int $id) : mixed;
    // public function getFeaturedRestaurants(): mixed;
}
