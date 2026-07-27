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

    public function deleteCategory(int $id): bool {
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

    private function clearMenuCache(int $restaurantId): void
    {
        Cache::tags([self::CACHE_TAG])->flush();
    }
}
