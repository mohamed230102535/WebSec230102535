<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Artisan;

class ShopPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Order Management permissions
        Permission::firstOrCreate(['name' => 'view_all_orders']);
        Permission::firstOrCreate(['name' => 'manage_orders']);
        Permission::firstOrCreate(['name' => 'cancel_orders']);
        Permission::firstOrCreate(['name' => 'view_own_orders']);

        // Create Cart & Checkout permissions
        Permission::firstOrCreate(['name' => 'use_cart']);
        Permission::firstOrCreate(['name' => 'place_orders']);
        Permission::firstOrCreate(['name' => 'cancel_own_orders']);

        // Create Review Management permissions
        Permission::firstOrCreate(['name' => 'write_reviews']);
        Permission::firstOrCreate(['name' => 'moderate_reviews']);
        Permission::firstOrCreate(['name' => 'view_reviews']);

        // Get roles
        $adminRole = Role::where('name', 'Admin')->first();
        $employeeRole = Role::where('name', 'Employee')->first();
        $customerRole = Role::where('name', 'Customer')->first();

        // Assign permissions to roles
        if ($adminRole) {
            $adminPermissions = [
                'view_all_orders', 'manage_orders', 'cancel_orders',
                'view_own_orders', 'use_cart', 'place_orders', 
                'cancel_own_orders', 'write_reviews', 'moderate_reviews', 'view_reviews'
            ];
            
            foreach ($adminPermissions as $permission) {
                $adminRole->givePermissionTo($permission);
            }
        }

        if ($employeeRole) {
            $employeePermissions = [
                'view_all_orders', 'manage_orders', 'moderate_reviews', 'view_reviews'
            ];
            
            foreach ($employeePermissions as $permission) {
                $employeeRole->givePermissionTo($permission);
            }
        }

        if ($customerRole) {
            $customerPermissions = [
                'use_cart', 'place_orders', 'cancel_own_orders', 
                'write_reviews', 'view_reviews', 'view_own_orders'
            ];
            
            foreach ($customerPermissions as $permission) {
                $customerRole->givePermissionTo($permission);
            }
        }

        // Clear permission cache
        Artisan::call('cache:clear');
    }
}
