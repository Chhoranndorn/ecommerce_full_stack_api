<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuoteController extends Controller
{
    /**
     * Get user's quotes (quotation list)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $quotes = Quote::where('user_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($quote) {
                return [
                    'id' => $quote->id,
                    'quote_number' => 'QT-' . str_pad($quote->id, 6, '0', STR_PAD_LEFT),
                    'status' => $quote->status,
                    'total' => $quote->total,
                    'customer_name' => $quote->customer_name,
                    'customer_email' => $quote->customer_email,
                    'items_count' => $quote->items->count(),
                    'valid_until' => $quote->valid_until,
                    'created_at' => $quote->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $quotes,
        ]);
    }

    /**
     * Get a specific quote with details
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $quote = Quote::where('user_id', $user->id)
            ->with('items')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quote->id,
                'quote_number' => 'QT-' . str_pad($quote->id, 6, '0', STR_PAD_LEFT),
                'status' => $quote->status,
                'total' => $quote->total,
                'customer_name' => $quote->customer_name,
                'customer_email' => $quote->customer_email,
                'customer_phone' => $quote->customer_phone,
                'customer_address' => $quote->customer_address,
                'notes' => $quote->notes,
                'valid_until' => $quote->valid_until,
                'items' => $quote->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'created_at' => $quote->created_at,
                'updated_at' => $quote->updated_at,
            ],
        ]);
    }

    /**
     * Create quote from cart (សម្រង់តម្លៃ)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'valid_days' => 'nullable|integer|min:1|max:90',
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

            // Use provided customer info or fallback to user info
            $customerName = $request->customer_name ?? $user->name;
            $customerEmail = $request->customer_email ?? $user->email;
            $customerPhone = $request->customer_phone ?? $user->phone;

            // Calculate validity date (default 30 days)
            $validDays = $request->valid_days ?? 30;
            $validUntil = Carbon::now()->addDays($validDays);

            // Create quote
            $quote = Quote::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'customer_address' => $request->customer_address,
                'notes' => $request->notes,
                'valid_until' => $validUntil,
            ]);

            // Create quote items from cart
            foreach ($cartItems as $cartItem) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_name' => $cartItem->variant ? $cartItem->variant->name_kh : null,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            // Note: We don't clear the cart for quotes (unlike orders)
            // Users might want to keep shopping or create another quote

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quote created successfully',
                'data' => [
                    'quote_id' => $quote->id,
                    'quote_number' => 'QT-' . str_pad($quote->id, 6, '0', STR_PAD_LEFT),
                    'total' => $quote->total,
                    'status' => $quote->status,
                    'valid_until' => $quote->valid_until,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quote',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update quote status or details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:pending,accepted,rejected,expired',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
        ]);

        $user = $request->user();

        $quote = Quote::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // Don't allow updating converted quotes
        if ($quote->status === 'converted') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update converted quotes',
            ], 400);
        }

        if ($request->has('status')) {
            $quote->status = $request->status;
        }

        if ($request->has('notes')) {
            $quote->notes = $request->notes;
        }

        if ($request->has('valid_until')) {
            $quote->valid_until = $request->valid_until;
        }

        $quote->save();

        return response()->json([
            'success' => true,
            'message' => 'Quote updated successfully',
            'data' => [
                'id' => $quote->id,
                'quote_number' => 'QT-' . str_pad($quote->id, 6, '0', STR_PAD_LEFT),
                'status' => $quote->status,
                'valid_until' => $quote->valid_until,
            ],
        ]);
    }

    /**
     * Delete a quote
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $quote = Quote::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // Don't allow deleting converted quotes
        if ($quote->status === 'converted') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete converted quotes',
            ], 400);
        }

        $quote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quote deleted successfully',
        ]);
    }

    /**
     * Convert quote to order
     */
    public function convertToOrder(Request $request, $id)
    {
        $request->validate([
            'shipping_address' => 'nullable|array',
        ]);

        $user = $request->user();

        $quote = Quote::where('user_id', $user->id)
            ->with('items')
            ->findOrFail($id);

        // Check if quote is already converted
        if ($quote->status === 'converted') {
            return response()->json([
                'success' => false,
                'message' => 'Quote has already been converted to an order',
            ], 400);
        }

        // Check if quote is expired
        if ($quote->valid_until && Carbon::parse($quote->valid_until)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Quote has expired',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create order from quote
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $quote->total,
                'shipping_address' => $request->shipping_address ?? [
                    'address' => $quote->customer_address,
                    'phone' => $quote->customer_phone,
                ],
            ]);

            // Create order items from quote items
            foreach ($quote->items as $quoteItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $quoteItem->product_id,
                    'product_variant_id' => $quoteItem->product_variant_id,
                    'product_name' => $quoteItem->product_name,
                    'variant_name' => $quoteItem->variant_name,
                    'quantity' => $quoteItem->quantity,
                    'price' => $quoteItem->price,
                    'subtotal' => $quoteItem->subtotal,
                ]);
            }

            // Update quote status
            $quote->status = 'converted';
            $quote->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quote converted to order successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'quote_id' => $quote->id,
                    'quote_number' => 'QT-' . str_pad($quote->id, 6, '0', STR_PAD_LEFT),
                    'total' => $order->total,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert quote to order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
