<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with('product.category')
            ->get()
            ->map(function ($wishlist) {
                $product = $wishlist->product;
                return [
                    'id' => $wishlist->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'image' => asset($product->image),
                        'price' => $product->price,
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => $product->category->name,
                        ] : null,
                    ],
                    'created_at' => $wishlist->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $wishlistItems,
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist',
            ], 400);
        }

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'data' => $wishlist,
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(Request $request, $productId)
    {
        $user = $request->user();

        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in wishlist',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
        ]);
    }

    /**
     * Toggle wishlist status (add if not exists, remove if exists)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
                'is_favorited' => false,
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
                'is_favorited' => true,
            ]);
        }
    }

    /**
     * Check if product is in user's wishlist
     */
    public function check(Request $request, $productId)
    {
        $user = $request->user();

        $isFavorited = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
        ]);
    }
}
