<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('banners')->delete();

        // Create Categories
        $categories = [
            ['name' => 'ខ្មៅញក', 'icon' => 'storage/icons/soup.png'],
            ['name' => 'បាយ', 'icon' => 'storage/icons/rice.png'],
            ['name' => 'ទឹកផ្លែ', 'icon' => 'storage/icons/juice.png'],
            ['name' => 'បង្អែម', 'icon' => 'storage/icons/dessert.png'],
            ['name' => 'ចានរំពះ', 'icon' => 'storage/icons/appetizer.png'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Add products for the first category (Soup/Noodles)
            if ($category->name === 'ខ្មៅញក') {
                $products = [
                    [
                        'name' => 'ខ្មៅញក្រុមណាបូរ',
                        'description' => 'Traditional Cambodian noodle soup with vegetables and herbs',
                        'price' => 2.00,
                        'image' => 'storage/products/noodle1.jpg',
                        'is_featured' => true,
                    ],
                    [
                        'name' => 'ខ្មៅញក្រុមប្រហែប',
                        'description' => 'Spicy noodle soup with chicken and fresh vegetables',
                        'price' => 2.00,
                        'image' => 'storage/products/noodle2.jpg',
                    ],
                    [
                        'name' => 'ខ្មៅញក្រុមប្រុប',
                        'description' => 'Yellow curry noodles with chicken and potatoes',
                        'price' => 2.00,
                        'image' => 'storage/products/noodle3.jpg',
                    ],
                    [
                        'name' => 'ខ្មៅញក្រុមន្យូ',
                        'description' => 'Rice noodles with beef and fresh herbs',
                        'price' => 2.00,
                        'image' => 'storage/products/noodle4.jpg',
                    ],
                    [
                        'name' => 'ខ្មៅញក្រុមត្រី កម',
                        'description' => 'Fish noodle soup with vegetables and lime',
                        'price' => 3.00,
                        'image' => 'storage/products/noodle5.jpg',
                        'is_featured' => true,
                    ],
                ];

                foreach ($products as $productData) {
                    $productData['category_id'] = $category->id;
                    Product::create($productData);
                }
            }

            // Add products for Rice category
            if ($category->name === 'បាយ') {
                $products = [
                    [
                        'name' => 'បាយសាច់គោ',
                        'description' => 'Fried rice with beef and vegetables',
                        'price' => 2.50,
                        'image' => 'storage/products/rice1.jpg',
                    ],
                    [
                        'name' => 'បាយសាច់មាន់',
                        'description' => 'Steamed rice with grilled chicken',
                        'price' => 2.50,
                        'image' => 'storage/products/rice2.jpg',
                    ],
                    [
                        'name' => 'បាយឆាសមុទ្រ',
                        'description' => 'Seafood fried rice with shrimp and squid',
                        'price' => 3.50,
                        'image' => 'storage/products/rice3.jpg',
                        'is_featured' => true,
                    ],
                ];

                foreach ($products as $productData) {
                    $productData['category_id'] = $category->id;
                    Product::create($productData);
                }
            }

            // Add products for Drinks category
            if ($category->name === 'ទឹកផ្លែ') {
                $products = [
                    [
                        'name' => 'ទឹកផ្លែឈើស្វាយ',
                        'description' => 'Fresh mango juice',
                        'price' => 1.50,
                        'image' => 'storage/products/drink1.jpg',
                    ],
                    [
                        'name' => 'ទឹកត្នោតចេក',
                        'description' => 'Coconut water',
                        'price' => 1.00,
                        'image' => 'storage/products/drink2.jpg',
                    ],
                ];

                foreach ($products as $productData) {
                    $productData['category_id'] = $category->id;
                    Product::create($productData);
                }
            }
        }

        // Create Banners
        Banner::create([
            'title' => 'Special Promotion',
            'image' => 'storage/banners/banner1.jpg',
            'link' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'New Menu Items',
            'image' => 'storage/banners/banner2.jpg',
            'link' => null,
            'order' => 2,
            'is_active' => true,
        ]);
    }
}
