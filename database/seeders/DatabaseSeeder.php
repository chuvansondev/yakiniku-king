<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'is_admin' => true,
            ]
        );

        $this->call([
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
            RestaurantSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
