<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions before each test
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }
}
