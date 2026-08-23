<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all permissions for the 'admin' guard
        $adminPermissions = Permission::where('guard_name', 'admin')->get();

        // Retrieve all permissions for the 'franchise' guard
        $franchisePermissions = Permission::where('guard_name', 'franchise')->get();

        // Retrieve all permissions for the 'cms' guard
        $cmsPermissions = Permission::where('guard_name', 'cms')->get();

        // Create or update 'admin' role with 'admin' guard and sync admin permissions
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'admin'],
            ['name' => 'admin', 'guard_name' => 'admin']
        );
        $adminRole->syncPermissions($adminPermissions);

        // Create or update 'franchise' role with 'franchise' guard and sync franchise permissions
        $franchiseRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'franchise'],
            ['name' => 'admin', 'guard_name' => 'franchise']
        );
        $franchiseRole->syncPermissions($franchisePermissions);

        // Create or update 'cms' role with 'cms' guard and sync cms permissions
        $cmsRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'cms'],
            ['name' => 'admin', 'guard_name' => 'cms']
        );
        $cmsRole->syncPermissions($cmsPermissions);
    }
}
