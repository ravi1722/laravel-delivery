<?php

namespace App\Repositories;

use App\Models\MenuCategory;

class MenuRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getMenuCategory(int $restaurantId)
    {
        return MenuCategory::withCount("items")->where("restaurant_id", $restaurantId)->active()->orderBy('sort_order')->get();
    }

    public function getMenuCategoryById(int $categoryId) {
        return MenuCategory::findOrFail($categoryId);
    }
    public function createCategory(array $data) {
        return MenuCategory::create($data);
    }
}
