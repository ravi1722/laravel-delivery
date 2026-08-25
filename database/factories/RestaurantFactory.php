<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' Restaurant';
        return [
            'owner_id' => User::factory()->create(['role' => 'restaurant_owner'])->id,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraph(),
            'cuisine_type' => fake()->randomElement(['North Indian', 'South Indian', 'Chinese', 'Pizza', 'Biryani']),
            'phone' => '9' . fake()->numerify('########'),
            'email' => fake()->companyEmail(),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Pune']),
            'state' => fake()->randomElement(['Maharashtra', 'Karnataka', 'Tamil Nadu']),
            'pincode' => fake()->numerify('######'),
            'commission_percentage' => 10.00,
            'minimum_order' => fake()->randomElement([99, 149, 199, 249]),
            'delivery_time' => fake()->randomElement([20, 25, 30, 40, 45]),
            'delivery_fee' => fake()->randomElement([0, 20, 30, 40, 49]),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(10, 500),
            'status' => 'active',
            'is_open' => true,
            'is_featured' => false,
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function closed(): static
    {
        return $this->state(['is_open' => false]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
