<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\DeliveryMethod;
use App\Models\Coupon;
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
            ->with(['items', 'deliveryMethod', 'coupon'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'delivery_fee' => $order->delivery_fee,
                'total' => $order->total,
                'shipping_address' => $order->shipping_address,
                'notes' => $order->notes,
                'delivery_method' => $order->deliveryMethod ? [
                    'id' => $order->deliveryMethod->id,
                    'name' => $order->deliveryMethod->name,
                    'name_kh' => $order->deliveryMethod->name_kh,
                ] : null,
                'coupon' => $order->coupon ? [
                    'code' => $order->coupon_code,
                    'discount_amount' => $order->discount_amount,
                ] : null,
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
            'delivery_method_id' => 'nullable|exists:delivery_methods,id',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
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
            // Calculate subtotal from cart items
            $subtotal = $cartItems->sum('subtotal');

            // Get delivery method and fee
            $deliveryFee = 0;
            $deliveryMethodId = $request->delivery_method_id;

            if ($deliveryMethodId) {
                $deliveryMethod = DeliveryMethod::find($deliveryMethodId);
                if ($deliveryMethod && $deliveryMethod->is_active) {
                    $deliveryFee = $deliveryMethod->price;
                }
            } else {
                // Default delivery fee logic (free if over $100)
                $deliveryFee = $subtotal >= 100 ? 0 : 1.00;
            }

            // Apply coupon if provided
            $discountAmount = 0;
            $couponId = null;
            $couponCode = null;

            if ($request->coupon_code) {
                $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

                if ($coupon && $coupon->isValid()) {
                    if (!$coupon->min_order_amount || $subtotal >= $coupon->min_order_amount) {
                        $discountAmount = $coupon->calculateDiscount($subtotal);
                        $couponId = $coupon->id;
                        $couponCode = $coupon->code;

                        // Increment coupon usage
                        $coupon->incrementUsage();
                    }
                }
            }

            // Calculate total
            $total = $subtotal + $deliveryFee - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'delivery_method_id' => $deliveryMethodId,
                'coupon_id' => $couponId,
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
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
                    'subtotal' => $order->subtotal,
                    'discount_amount' => $order->discount_amount,
                    'delivery_fee' => $order->delivery_fee,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'coupon_applied' => $couponCode ? true : false,
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
            ->with(['items', 'user', 'deliveryMethod', 'coupon'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'order_date' => $order->created_at->format('d/m/Y'),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'customer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],
                'shipping_address' => $order->shipping_address,
                'delivery_method' => $order->deliveryMethod ? [
                    'name' => $order->deliveryMethod->name,
                    'name_kh' => $order->deliveryMethod->name_kh,
                ] : null,
                'coupon' => $order->coupon ? [
                    'code' => $order->coupon_code,
                    'discount_amount' => $order->discount_amount,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'delivery_fee' => $order->delivery_fee,
                'total' => $order->total,
                'notes' => $order->notes,
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
