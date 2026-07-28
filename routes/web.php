<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use App\Http\Controllers\Restaurant\MenuCategoryController as RestaurantMenuCategoryController;
use App\Http\Controllers\Restaurant\MenuItemController as RestaurantMenuItemController;
use App\Http\Controllers\Restaurant\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// ── Authenticated Routes ───────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Restaurant management
    Route::resource('restaurants', AdminRestaurantController::class);
    Route::post('restaurants/{restaurant}/approve', [AdminRestaurantController::class, 'approve'])
        ->name('restaurants.approve');
    Route::post('restaurants/{restaurant}/toggle-status', [AdminRestaurantController::class, 'toggleStatus'])
        ->name('restaurants.toggle-status');
});

Route::middleware(['auth', 'role:restaurant_owner'])->prefix('restaurant')->name('restaurant.')->group(function () {
    Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->name('dashboard');

    // Restaurant profile
    Route::get('/profile/setup', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/get-city-state/{pincode}', [ProfileController::class, 'getCityState'])->name('profile.getCityState');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // // Menu categories
    Route::resource('menu-categories', RestaurantMenuCategoryController::class)
        ->except(['show', 'create', 'edit']);

    // // Menu items
    Route::resource('menu-items', RestaurantMenuItemController::class);
    Route::post('menu-items/{id}/toggle', [RestaurantMenuItemController::class, 'toggle'])
        ->name('menu-items.toggle');

    Route::post('/toggle-open', function () {
        $restaurant = Auth::user()->restaurant;
        $restaurant->update([
            'is_open' => !$restaurant->is_open,
        ]);
        return back();
    })->name('toggle-open');
});

Route::middleware(['auth', 'role:delivery_agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', function () {
        return view('agent.dashboard');
    })->name('dashboard');
});

// pending: Admin/RestaurantController, resources/views/admin/restaurants/index.blade.php, 