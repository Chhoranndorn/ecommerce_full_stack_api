<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Special;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        // Get all active banners ordered by order field
        $banners = Banner::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => asset($item->image),
                    'link' => $item->link,
                ];
            });

        // Get all specials
        $specials = Special::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'image' => asset($item->image),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        // Get all categories
        $categories = Category::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'icon' => asset($item->icon),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        // Get featured products
        $featuredProducts = Product::with('category')
            ->where('is_featured', true)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image' => asset($item->image),
                    'price' => $item->price,
                    'category_id' => $item->category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'name' => $item->category->name,
                    ] : null,
                    'is_featured' => $item->is_featured,
                ];
            });

        // Get all products with category info
        $products = Product::with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image' => asset($item->image),
                    'price' => $item->price,
                    'category_id' => $item->category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'name' => $item->category->name,
                    ] : null,
                    'is_featured' => $item->is_featured,
                ];
            });

        return response()->json([
            'banners' => $banners,
            'specials' => $specials,
            'categories' => $categories,
            'featured_products' => $featuredProducts,
            'products' => $products,
        ]);
    }
}
