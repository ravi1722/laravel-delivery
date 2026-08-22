<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\RestaurantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function () {
    // ── Public Routes — rate limited for guests ─────────────────
    Route::middleware('throttle:api')->group(function () {
        // Auth
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('register', [AuthController::class, 'register'])->name('register');
            Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1'); // Extra protection on login
        });

        // Public restaurant browsing
        Route::prefix('restaurants')->name('restaurants.')->group(function () {
            Route::get('/', [RestaurantController::class, 'index'])->name('index');
            Route::get('/featured', [RestaurantController::class, 'featured'])->name('featured');
            Route::get('/{slug}', [RestaurantController::class, 'show'])->name('show');
            Route::get('/{restaurant}/menu', [RestaurantController::class, 'menu'])->name('menu');
            Route::get('/{restaurant}/menu/search', [RestaurantController::class, 'searchMenu'])->name('menu.search');
        });
    });

    // ── Authenticated Routes ────────────────────────────────────
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        // Auth
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        });

        // Cart
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::put('/{cartItemId}', [CartController::class, 'update'])->name('update');
            Route::delete('/{cartItemId}', [CartController::class, 'remove'])->name('remove');
            Route::delete('/', [CartController::class, 'clear'])->name('clear');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::get('/{order}/track', [OrderController::class, 'track'])->name('track');
        });
    });
});

// ── API Health Check ────────────────────────────────────────────
Route::get('health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'up';
    } catch (\Exception $e) {
        $dbStatus = 'down';
    }

    try {
        // Cache::store('redis')->set('health', true, 10);
        $cacheStatus = 'up';
    } catch (\Exception $e) {
        $cacheStatus = 'down';
    }

    return response()->json([
        'status'    => 'healthy',
        'version'   => 'v1',
        'timestamp' => now()->toIso8601String(),
        'services'  => [
            'database' => $dbStatus,
            'cache'    => $cacheStatus,
        ],
    ]);
});
