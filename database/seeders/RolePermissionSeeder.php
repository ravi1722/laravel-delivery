<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Restaurant permissions
            'view restaurants',
            'create restaurants',
            'edit restaurants',
            'delete restaurants',
            // Menu permissions
            'view menu',
            'create menu',
            'edit menu',
            'delete menu',
            // Order permissions
            'view orders',
            'manage orders',
            'cancel orders',
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            // Report permissions
            'view reports',
            'export reports',
            // Coupon permissions
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            // Delivery permissions
            'manage deliveries',
            'view deliveries',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Super Admin — all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $ownerRule = Role::firstOrCreate(['name'=> 'restaurant_owner']);
        $ownerRule->givePermissionTo([
            'view menu',
            'create menu',
            'edit menu',
            'delete menu',
            'view orders',
            'manage orders',
            'view reports'
        ]);

        // Delivery Agent
        $agentRole = Role::firstOrCreate(['name' => 'delivery_agent']);
        $agentRole->givePermissionTo([
            'view orders',
            'manage deliveries',
            'view deliveries',
        ]);

        // Customer
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view restaurants',
            'view menu',
            'view orders',
            'cancel orders'
        ]);

        // Create Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'l_delivery@yopmail.com'],
            [
                'name' => 'Super Admin',
                'password' => '123456789',
                'phone' => '9875463210',
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );

        $user->assignRole('admin');
        $this->command->info('Roles and permission created successfully..!');

    }
}
