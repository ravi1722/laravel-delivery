<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RestaurantController;
use Illuminate\Http\Request;
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
    });
});
