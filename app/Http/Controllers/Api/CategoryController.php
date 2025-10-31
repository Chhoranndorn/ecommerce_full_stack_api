<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => asset($category->icon),
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get a specific category with its products
     */
    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $products = $category->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => asset($product->image),
                'price' => $product->price,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => asset($category->icon),
                'products' => $products,
            ],
        ]);
    }
}
