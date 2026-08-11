<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use App\Http\Controllers\Restaurant\MenuCategoryController as RestaurantMenuCategoryController;
use App\Http\Controllers\Restaurant\MenuItemController as RestaurantMenuItemController;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Restaurant\OrderController as RestaurantOrderController;
use App\Http\Controllers\Restaurant\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ---------------Customer---------------
Route::get('/', [CustomerHomeController::class, 'index'])->name('customer.home');
Route::get('/restaurants/{restaurant}', [CustomerHomeController::class, 'show'])->name('customer.restaurant');

// ── Authenticated Routes ───────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cart
    Route::prefix('cart')->name('customer.cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{cartItemId}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cartItemId}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });

    // Addresses
    Route::prefix('addresses')->name('customer.addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::put('/{id}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{id}', [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/default', [AddressController::class, 'setDefault'])->name('set-default');
    });

    // Orders
    Route::prefix('orders')->name('customer.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/apply-coupon', [OrderController::class, 'applyCoupon'])->name('apply-coupon');
    });
});

// ------------- Login - Register ------------
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// -------------Admin--------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Restaurant management
    Route::resource('restaurants', AdminRestaurantController::class);
    Route::post('restaurants/{restaurant}/approve', [AdminRestaurantController::class, 'approve'])
        ->name('restaurants.approve');
    Route::post('restaurants/{restaurant}/toggle-status', [AdminRestaurantController::class, 'toggleStatus'])
        ->name('restaurants.toggle-status');
});

// --------------restaurant owner---------------
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

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [RestaurantOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [RestaurantOrderController::class, 'show'])->name('show');
        Route::post('/{order}/status', [RestaurantOrderController::class, 'updateStatus'])->name('update-status');
    });
});

// --------Delivery Agent-----------
Route::middleware(['auth', 'role:delivery_agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', function () {
        return view('agent.dashboard');
    })->name('dashboard');
});

// Notifications
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    // Route::get('/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])
    //     ->name('unread');
    // Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
    //     ->name('read');
    // Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
    //     ->name('read-all');
    // Route::delete('/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])
    //     ->name('destroy');
});

// pending: Admin/RestaurantController, resources/views/admin/restaurants/index.blade.php, 