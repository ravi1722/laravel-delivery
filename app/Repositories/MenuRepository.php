<?php

namespace App\Repositories;

use App\Models\ItemVariant;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuRepository
{
    public function getMenuCategory(int $restaurantId)
    {
        return MenuCategory::with([
            'items' => fn($q) => $q->available()
        ])
            ->withCount([
                'items' => fn($q) => $q->available()
            ])
            ->where("restaurant_id", $restaurantId)->active()->orderBy('sort_order')->get();
    }

    public function getMenuCategoryById(int $categoryId)
    {
        return MenuCategory::findOrFail($categoryId);
    }
    public function createCategory(array $data)
    {
        return MenuCategory::create($data);
    }

    public function getMenuItemFromRestaurant(int $restaurantId)
    {
        return MenuItem::with(['category:id,name', 'variants', 'addons'])->where('restaurant_id', $restaurantId);
    }

    public function createItem(array $data)
    {
        $items =  MenuItem::create($data);

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                $items->variants()->create($variant);
            }
        }

        if (!empty($data['addons'])) {
            foreach ($data['addons'] as $addons) {
                $items->addons()->create($addons);
            }
        }

        return $items;
    }

    public function getMenuItem(int $id)
    {
        return MenuItem::findOrFail($id);
    }

    public function getItemVariant(int $id)
    {
        return ItemVariant::findOrFail($id);
    }

    public function getAddons(array $ids) {
        return "";
    }
}
