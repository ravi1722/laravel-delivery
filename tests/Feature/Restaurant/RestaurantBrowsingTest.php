<?php

use App\Models\Restaurant;

describe('Restaurant Browsing', function () {
    test('can list active restaurants', function () {
        ['restaurant' => $restaurant] = createRestaurantOwner();
        Restaurant::factory()->create(['status' => 'pending']); // should not appear

        $response = $this->getJson('/api/v1/restaurants');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page'],
            ]);

        // Only active restaurants should appear
        $data = $response->json('data');
        collect($data)->each(fn($r) => expect($r['status']['status'])->toBe('active'));
    });

    // test('can get featured restaurants', function () {
    //     ['restaurant' => $featured]    = createRestaurantOwner(['is_featured' => true]);
    //     ['restaurant' => $notFeatured] = createRestaurantOwner(['is_featured' => false]);

    //     $response = $this->getJson('/api/v1/restaurants/featured');
    //     dd($response);

    //     $response->assertOk();
    //     $ids = collect($response->json('data'))->pluck('id')->toArray();
    //     expect($ids)->toContain($featured->id)
    //         ->not->toContain($notFeatured->id);
    // });

    test('can get restaurant by slug', function () {
        ['restaurant' => $restaurant] = createRestaurantOwner();

        $response = $this->getJson("/api/v1/restaurants/{$restaurant->slug}");

        $response->assertOk()
            ->assertJsonPath('data.id', $restaurant->id)
            ->assertJsonPath('data.name', $restaurant->name)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'slug', 'address', 'settings', 'stats'],
            ]);
    });

    // test('returns 404 for non-existent restaurant', function () {
    //     $this->getJson('/api/v1/restaurants/non-existent-slug')
    //         ->assertNotFound();
    // });

    // test('can get restaurant menu', function () {
    //     ['restaurant' => $restaurant] = createRestaurantOwner();
    //     $item = createMenuItem($restaurant);

    //     $response = $this->getJson("/api/v1/restaurants/{$restaurant->id}/menu");

    //     $response->assertOk()
    //         ->assertJsonStructure([
    //             'data' => [
    //                 '*' => ['id', 'name', 'items'],
    //             ],
    //         ]);
    // });

    // test('can search restaurants by name', function () {
    //     ['restaurant' => $pizza]   = createRestaurantOwner(['name' => 'Pizza Palace', 'slug' => 'pizza-palace']);
    //     ['restaurant' => $biryani] = createRestaurantOwner(['name' => 'Biryani House', 'slug' => 'biryani-house']);

    //     $response = $this->getJson('/api/v1/restaurants?search=pizza');

    //     $response->assertOk();

    //     $ids = collect($response->json('data'))->pluck('id')->toArray();
    //     expect($ids)->toContain($pizza->id)
    //         ->not->toContain($biryani->id);
    // });

    // test('can filter restaurants by city', function () {
    //     ['restaurant' => $mumbai]    = createRestaurantOwner(['city' => 'Mumbai']);
    //     ['restaurant' => $bangalore] = createRestaurantOwner(['city' => 'Bangalore']);

    //     $response = $this->getJson('/api/v1/restaurants?city=Mumbai');

    //     $response->assertOk();

    //     $ids = collect($response->json('data'))->pluck('id')->toArray();
    //     expect($ids)->toContain($mumbai->id)
    //         ->not->toContain($bangalore->id);
    // });
});
