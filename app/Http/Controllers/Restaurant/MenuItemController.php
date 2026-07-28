<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\MenuServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreMenuItemRequest;
use App\Http\Requests\Menu\UpdateMenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    public function __construct(private MenuServiceInterface $menuService) {}
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);
        $items      = $this->menuService->getItemsByRestaurant($restaurant->id, request()->all());

        return view('restaurant.menu.items.index', compact('items', 'categories', 'restaurant'));
    }

    public function create()
    {
        $restaurant = Auth::user()->restaurant;
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);

        return view('restaurant.menu.items.create', compact('categories', 'restaurant'));
    }

    public function store(StoreMenuItemRequest $request)
    {
        $data = $request->validated();
        $data['restaurant_id'] = Auth::user()->restaurant->id;

        $this->menuService->createItem($data);

        return redirect()->route('restaurant.menu-items.index')
            ->with('success', 'Menu item added successfully!');
    }

    public function edit(MenuItem $menuItem)
    {
        $this->authorize('manage', $menuItem);

        $restaurant = Auth::user()->restaurant;
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);

        return view('restaurant.menu.items.edit', compact('menuItem', 'categories', 'restaurant'));
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        $this->authorize('manage', $menuItem);

        $this->menuService->updateItem($menuItem->id, $request->validated());

        return redirect()->route('restaurant.menu-items.index')
            ->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('manage', $menuItem);

        $this->menuService->deleteItem($menuItem->id);

        return back()->with('success', 'Menu item deleted successfully!');
    }

    public function toggle(int $id)
    {
        $item = $this->menuService->toggleItemAvailability($id);

        return response()->json([
            'success'      => true,
            'is_available' => $item->is_available,
            'message'      => $item->is_available ? 'Item is now available' : 'Item marked unavailable',
        ]);
    }
}
