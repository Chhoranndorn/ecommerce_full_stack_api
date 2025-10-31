<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with optional category filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => asset($product->image),
                'price' => $product->price,
                'category_id' => $product->category_id,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'icon' => asset($product->category->icon),
                ] : null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get a specific product with variants and related products
     */
    public function show(Request $request, $id)
    {
        $product = Product::with(['category', 'variants'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get()
            ->map(function ($relatedProduct) {
                return [
                    'id' => $relatedProduct->id,
                    'name' => $relatedProduct->name,
                    'image' => asset($relatedProduct->image),
                    'price' => $relatedProduct->price,
                ];
            });

        // Check if product is favorited by current user
        $isFavorited = false;
        if ($request->user()) {
            $isFavorited = Wishlist::where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->exists();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => asset($product->image),
                'price' => $product->price,
                'is_favorited' => $isFavorited,
                'category_id' => $product->category_id,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'icon' => asset($product->category->icon),
                ] : null,
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'name_kh' => $variant->name_kh,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                        'is_available' => $variant->is_available,
                    ];
                }),
                'related_products' => $relatedProducts,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ],
        ]);
    }

    /**
     * Search products by name or description
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        if (empty($query)) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $products = Product::with('category')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'image' => asset($product->image),
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'icon' => asset($product->category->icon),
                    ] : null,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get new/latest products
     */
    public function newProducts(Request $request)
    {
        $limit = $request->input('limit', 10);

        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'image' => asset($product->image),
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'icon' => asset($product->category->icon),
                    ] : null,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
