<?php

use App\Models\User;

// for pest test check .env.testing file, TestCase.php. phpunit.xml

describe('authentication', function () {
    test('customer can register successfully', function () {
        $response = $this->postJson('api/v1/auth/register', [
            'name'                  => 'Ravi Kumar',
            'email'                 => 'customer1@yopmail.com',
            'phone'                 => '9876543210',
            'password'              => '123456789',
            'password_confirmation' => '123456789',
            'role'                  => 'customer',
        ]);
        $response->assertCreated()->assertJsonStructure([
            'success',
            'data' => ['user', 'token'],
        ]);

        $this->assertDatabaseHas('users', ['email' => 'customer1@yopmail.com']);
        $this->assertDatabaseHas('wallets', [
            'user_id' => User::where('email', 'customer1@yopmail.com')->first()->id,
        ]);
    });

    test('registration fails with duplicate email', function () {
        User::factory()->create(['email' => 'customer1@yopmail.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Ravi Kumar',
            'email'                 => 'customer1@yopmail.com',
            'phone'                 => '9876543210',
            'password'              => '123456789',
            'password_confirmation' => '123456789',
            'role'                  => 'customer',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    });

    test('registration fails with invalid phone number', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@yopmail.com',
            'phone'                 => '1234567890', // invalid — must start with 6-9
            'password'              => '123456789',
            'password_confirmation' => '123456789',
            'role'                  => 'customer',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    });

    test('customer can login with correct credentials', function () {
        $user = User::factory()->create([
            'email'    => 'ravi@test.com',
            'password' => '123456789',
            'role'     => 'customer',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'ravi@test.com',
            'password' => '123456789',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['user', 'token'],
            ]);
    });

    test('login fails with wrong password', function () {
        User::factory()->create(['email' => 'test@yopmail.com']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'ravi@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    });

    test('authenticated user can get their profile', function () {
        $user = createCustomer();       //From Pest.php

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.role', 'customer');
    });

    test('authenticated user can logout', function () {
        $user  = createCustomer();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/v1/auth/logout');

        $response->assertOk();
        // Token should be revoked
        $this->assertDatabaseCount('personal_access_tokens', 0);
    });

    test('unauthenticated access returns 401', function () {
        $this->getJson('/api/v1/auth/me')
            ->assertUnauthorized();
    });
});

// for check this test using        php artisan test tests/Feature/Auth/AuthTest.php
// for all test run                 php artisan test
