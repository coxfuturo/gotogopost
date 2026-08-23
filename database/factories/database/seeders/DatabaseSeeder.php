<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(Admin::count() == 0){
            $user = Admin::create([
                'name' => 'IndiaPost',
                'email' => 'indiapost@gmail.com',
                'phone' => '9999999999',
                'password' => Hash::make('company@123'),
            ]);
            $user->assignRole('admin');
        }
    }
}
