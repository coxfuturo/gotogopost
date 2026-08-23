<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;


class FranchisePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            collect([
                [
                    'name' => 'Dashboard'
                ],
                [
                    'name' => 'Admin Role'
                ],
                [
                    'name' => 'Franchise'
                ],
                [
                    'name' => 'Users'
                ],
                [
                    'name' => 'cms'
                ],
                [
                    'name' => 'KYCS'
                ],
                [
                    'name' => 'Reports'
                ],

                [
                    'name' => 'Exports'
                ],

                [
                    'name' => 'Imports'
                ],

                [
                    'name' => 'Support Ticket'
                ],

                [
                    'name' => 'Web Settings'
                ],

            ])->each(function ($permission) {
                Permission::create([
                    'name' => $permission['name'] . '-view',
                    'guard_name' => 'franchise'
                ]);
                Permission::create([
                    'name' => $permission['name'] . '-create',
                    'guard_name' => 'franchise'
                ]);
                Permission::create([
                    'name' => $permission['name'] . '-edit',
                    'guard_name' => 'franchise'
                ]);
                Permission::create([
                    'name' => $permission['name'] . '-delete',
                    'guard_name' => 'franchise'
                ]);
            });
        });
    }
}
