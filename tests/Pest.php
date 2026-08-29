<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;

pest()->extend(Tests\TestCase::class)
    // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

// Create admin user
function createAdmin(): User
{
    $user = User::factory()->create([
        'role'  => 'admin',
        'email' => 'admin@test.com',
    ]);
    $user->assignRole('admin');
    return $user;
}

// Create restaurant owner with restaurant
function createRestaurantOwner(array $restaurantData = []): array
{
    $owner = User::factory()->create(['role' => 'restaurant_owner']);
    $owner->assignRole('restaurant_owner');

    $restaurant = Restaurant::factory()->create(array_merge([
        'owner_id' => $owner->id,
        'status'   => 'active',
        'is_open'  => true,
    ], $restaurantData));

    return compact('owner', 'restaurant');
}

// Create customer
function createCustomer(): User
{
    $user = User::factory()->create(['role' => 'customer']);
    $user->assignRole('customer');

    // Create wallet
    \App\Models\Wallet::factory()->create(['user_id' => $user->id]);

    return $user;
}

// Create menu item
function createMenuItem(Restaurant $restaurant, array $data = []): MenuItem
{
    $category = MenuCategory::factory()->create([
        'restaurant_id' => $restaurant->id,
    ]);

    return MenuItem::factory()->create(array_merge([
        'restaurant_id' => $restaurant->id,
        'category_id'   => $category->id,
        'price'         => 150,
        'is_available'  => true,
    ], $data));
}
