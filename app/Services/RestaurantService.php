<?php

namespace App\Services;

use App\Contracts\RestaurantServiceInterface;
use App\Repositories\RestaurantRepository;
use Illuminate\Support\Facades\Storage;

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
        return $this->restaurantRepository->findByOwner($ownerId);
    }

    public function createRestaurant(array $data): mixed
    {
        if (!empty($data['logo'])) {
            $data['logo'] = uploadImage($data['logo'], 'restaurants/logos');
        }

        if (!empty($data['cover_image'])) {
            $data['cover_image'] = uploadImage($data['cover_image'], 'restaurants/cover-images');
        }

        $restaurant = $this->restaurantRepository->storeRestraurant($data);

        // $this->restaurantRepository->clearCache();

        return $restaurant;
    }
    public function updateRestaurant(int $id, array $data): mixed
    {
        $restaurant = $this->restaurantRepository->findById($id);

        if (!empty($data['logo'])) {
            if ($restaurant->logo) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $data['logo'] = uploadImage($data['logo'], 'restaurants/logos');
        }

        if (!empty($data['cover_image'])) {
            if ($restaurant->cover_image) {
                Storage::disk('public')->delete($restaurant->cover_image);
            }

            $data['cover_image'] = uploadImage($data['cover_image'], 'restaurants/cover-images');
        }

        $restaurant->update($data);

        // $this->restaurantRepository->clearCache();

        return $restaurant;
    }
    public function deleteRestaurant(int $id): bool
    {
        $restaurant = $this->restaurantRepository->findById($id);

        if ($restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }

        if ($restaurant->cover_image) {
            Storage::disk('public')->delete($restaurant->cover_image);
        }
        $result = $restaurant->delete();

        // $this->repository->clearCache();

        return $result;
    }
    public function toggleStatus(int $id): mixed
    {
        $restaurant = $this->restaurantRepository->findById($id);
        $restaurant->update(['is_open' => !$restaurant->is_open]);

        // $this->repository->clearCache();

        return $restaurant->fresh();
    }
    public function approveRestaurant(int $id): mixed
    {
        $restaurant = $this->restaurantRepository->findById($id);
        $restaurant->update(['status' => 'active']);

        // $this->repository->clearCache();

        return $restaurant->fresh();
    }
    public function getFeaturedRestaurants(): mixed
    {
        return $this->restaurantRepository->getFeatured();
    }

    public function getRestaurantCities () : mixed {
        return $this->restaurantRepository->getRestaurantCities();
    }
}
