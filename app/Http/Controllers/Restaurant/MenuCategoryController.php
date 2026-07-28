<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\MenuServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends Controller
{
    public function __construct(private MenuServiceInterface $menuService) {}
    public function index()
    {

        $restaurant = Auth::user()->restaurant;
        $categories = $this->menuService->getCategoriesByRestaurant($restaurant->id);

        return view('restaurant.menu.categories.index', compact('categories', 'restaurant'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['restaurant_id'] = Auth::user()->restaurant->id;

        $this->menuService->createCategory($data);
        return back()->with('success', 'Category added successfully!');
    }

    public function update(StoreCategoryRequest $request, int $id)
    {
        $this->menuService->updateCategory($id, $request->validated());
        return back()->with('success', 'Category added successfully!');
    }

    public function destroy(int $id)
    {
        try {
            $this->menuService->deleteCategory($id);
            return back()->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
