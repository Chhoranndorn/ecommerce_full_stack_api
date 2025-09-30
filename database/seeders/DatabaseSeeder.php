<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Special;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::firstOrCreate(
    ['email' => 'test@example.com'], // check if email exists
    [
        'name' => 'Test User',
        'password' => bcrypt('password123'), // required since factory auto-generates
    ]
);

        Special::create([
            'name' => 'Discount Fruits',
            'image' => 'storage/specials/fruits.png',
        ]);
        Category::create([
            'name' => 'Vegetables',
            'icon' => 'storage/icons/veg.png',
        ]);
        Product::create([
            'name' => 'Tomato',
            'image' => 'storage/products/tomato.png',
            'price' => 1.50
        ]);

    }
}
