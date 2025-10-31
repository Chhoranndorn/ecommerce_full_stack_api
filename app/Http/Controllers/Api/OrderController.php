<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total' => $order->total,
                    'items_count' => $order->items->count(),
                    'created_at' => $order->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Get a specific order with invoice details
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::where('user_id', $user->id)
            ->with('items')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'status' => $order->status,
                'total' => $order->total,
                'shipping_address' => $order->shipping_address,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ],
        ]);
    }

    /**
     * Create order from cart (Checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'nullable|array',
        ]);

        $user = $request->user();

        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $total = $cartItems->sum('subtotal');

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'shipping_address' => $request->shipping_address,
            ]);

            // Create order items from cart
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_name' => $cartItem->variant ? $cartItem->variant->name_kh : null,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'total' => $order->total,
                    'status' => $order->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get invoice/receipt for an order
     */
    public function invoice(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::where('user_id', $user->id)
            ->with(['items', 'user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'order_date' => $order->created_at->format('d/m/Y'),
                'status' => $order->status,
                'customer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],
                'shipping_address' => $order->shipping_address,
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'subtotal' => $order->total,
                'tax' => 0,
                'delivery_fee' => 0,
                'total' => $order->total,
            ],
        ]);
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be cancelled',
            ], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
        ]);
    }
}
