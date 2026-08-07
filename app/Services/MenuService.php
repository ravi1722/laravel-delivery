<?php

namespace App\Services;

use App\Contracts\MenuServiceInterface;
use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Override;

class MenuService implements MenuServiceInterface
{
    const CACHE_TAG = 'menu';
    const CACHE_TTL = 3600;
    /**
     * Create a new class instance.
     */
    public function __construct(private MenuRepository $menuRepository)
    {
        //
    }

    public function getCategoriesByRestaurant(int $restaurantId): mixed
    {
        // return cacheRemember(self::CACHE_TAG, 'menu.categories.{$restaurantId}', self::CACHE_TTL, function() use ($restaurantId) {
        return $this->menuRepository->getMenuCategory($restaurantId);
        // });
    }

    #[Override]
    public function createCategory(array $data): mixed
    {
        if (!empty($data['image'])) {
            $data['image'] = uploadImage($data['image'], 'menus/categories');
        }
        $category = $this->menuRepository->createCategory($data);
        // $this->clearMenuCache($data['restaurant_id']);
        return $category;
    }

    public function updateCategory(int $id, array $data): mixed
    {
        $category = $this->menuRepository->getMenuCategoryById($id);
        if (!empty($data['image'])) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        }
        $category->update($data);
        // $this->clearMenuCache($category->restaurant_id);

        return $category->fresh();
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->menuRepository->getMenuCategoryById($id);

        if ($category->items()->count() > 0) {
            throw new \Exception('Cannot delete category with menu items. Please remove items first.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $restaurantId = $category->restaurant_id;
        $result = $category->delete();
        // $this->clearMenuCache($restaurantId);

        return $result;
    }

    public function getItemsByRestaurant(int $restaurantId, array $filters = []): mixed
    {
        $cacheKey = "menu.items.restaurant.{$restaurantId}." . md5(serialize($filters));

        // return cacheRemember(self::CACHE_TAG, $cacheKey, now()->addDay(), function () use ($restaurantId, $filters) {
        $query = $this->menuRepository->getMenuItemFromRestaurant($restaurantId);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['food_type'])) {
            $query->where('food_type', $filters['food_type']);
        }

        if (!empty($filters['is_available'])) {
            $query->available();
        }

        if (!empty($filters['search'])) {
            $query->whereFullText(['name', 'description'], $filters['search']);
        }

        return $query->orderBy('sort_order')->paginate(20);
        // });
    }

    public function createItem(array $data): mixed
    {
        if (!empty($data['image'])) {
            $data['image'] = uploadImage($data['image'], 'menu/items');
        }

        $items = $this->menuRepository->createItem($data);
        return $items->load(['variants', 'addons']);
    }

    public function updateItem(int $id, array $data): mixed
    {
        $item = $this->menuRepository->getMenuItem($id);
        if (!empty($data['image'])) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = uploadImage($data['image'], 'menu/items');
        }
        $item->update($data);
        // $this->clearMenuCache($item->restaurant_id);

        return $item->fresh(['variants', 'addons']);
    }

    public function deleteItem(int $id): bool
    {
        $item = $this->menuRepository->getMenuItem($id);
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $restaurantId = $item->restaurant_id;
        $result = $item->delete();
        // $this->clearMenuCache($restaurantId);

        return $result;
    }

    public function toggleItemAvailability(int $id): mixed
    {
        $item = $this->menuRepository->getMenuItem($id);
        $item->update(['is_available' => !$item->is_available]);

        // $this->clearMenuCache($item->restaurant_id);

        return $item->fresh();
    }

    public function getMenuItemById(int $id): mixed
    {
        return $this->menuRepository->getMenuItem($id);
    }

    public function getItemVariantById(int $id): mixed {
        return $this->menuRepository->getItemVariant($id);
    }

    public function getAddonsByIds(array $ids): mixed {
        return $this->menuRepository->getAddons($ids);
    }

    private function clearMenuCache(int $restaurantId): void
    {
        Cache::tags([self::CACHE_TAG])->flush();
    }
}
