<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product', 'variant'])
            ->get()
            ->map(function ($cart) {
                return [
                    'id' => $cart->id,
                    'product' => [
                        'id' => $cart->product->id,
                        'name' => $cart->product->name,
                        'image' => asset($cart->product->image),
                    ],
                    'variant' => $cart->variant ? [
                        'id' => $cart->variant->id,
                        'name' => $cart->variant->name,
                        'name_kh' => $cart->variant->name_kh,
                    ] : null,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                    'subtotal' => $cart->subtotal,
                ];
            });

        $total = $cartItems->sum('subtotal');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->count(),
            ],
        ]);
    }

    /**
     * Add product to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $user = $request->user();
        $quantity = $request->quantity ?? 1;

        // Get product and determine price
        $product = Product::findOrFail($request->product_id);
        $price = $product->price;

        // If variant is selected, use variant price
        if ($request->product_variant_id) {
            $variant = ProductVariant::findOrFail($request->product_variant_id);
            $price = $variant->price;
        }

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingCart) {
            // Update quantity
            $existingCart->quantity += $quantity;
            $existingCart->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'data' => $existingCart,
            ]);
        }

        // Create new cart item
        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id,
            'quantity' => $quantity,
            'price' => $price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'data' => $cart,
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ],
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $deleted = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
        ]);
    }

    /**
     * Clear all items from cart
     */
    public function clear(Request $request)
    {
        $user = $request->user();

        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
        ]);
    }

    /**
     * Increment cart item quantity
     */
    public function increment(Request $request, $id)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $cart->quantity += 1;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'data' => [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ],
        ]);
    }

    /**
     * Decrement cart item quantity
     */
    public function decrement(Request $request, $id)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($cart->quantity <= 1) {
            // Remove item if quantity would be 0
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
            ]);
        }

        $cart->quantity -= 1;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'data' => [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ],
        ]);
    }
}
