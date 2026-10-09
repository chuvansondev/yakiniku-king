<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        // $beef = MenuCategory::where(
        //     'slug',
        //     'thit-bo'
        // )->first();

        // MenuItem::create([
        //     'category_id' => $beef->id,
        //     'name' => 'Wagyu',
        //     'slug' => 'wagyu',
        //     'description' => 'Thịt bò Wagyu',
        //     'price' => 299000,
        //     'is_must_try' => true,
        //     'sort_order' => 1,
        //     'status' => true,
        // ]);

        // MenuItem::create([
        //     'category_id' => $beef->id,
        //     'name' => 'Beef Karubi',
        //     'slug' => 'beef-karubi',
        //     'description' => 'Thịt bò Karubi',
        //     'price' => 199000,
        //     'is_must_try' => true,
        //     'sort_order' => 2,
        //     'status' => true,
        // ]);
    }
}
