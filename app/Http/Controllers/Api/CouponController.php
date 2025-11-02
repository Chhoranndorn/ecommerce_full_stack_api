<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate/Verify coupon code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 404);
        }

        if (!$coupon->isValid()) {
            $message = 'Coupon is not valid';

            if (!$coupon->is_active) {
                $message = 'Coupon is inactive';
            } elseif ($coupon->valid_until && now()->isAfter($coupon->valid_until)) {
                $message = 'Coupon has expired';
            } elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                $message = 'Coupon usage limit reached';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);
        }

        // Get subtotal from cart if not provided
        $subtotal = $request->subtotal;
        if ($subtotal === null) {
            $user = $request->user();
            $subtotal = Cart::where('user_id', $user->id)->sum('subtotal');
        }

        // Check minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum order amount of \${$coupon->min_order_amount} required",
            ], 400);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => [
                'coupon_id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => $discount,
                'subtotal' => $subtotal,
                'new_subtotal' => $subtotal - $discount,
            ],
        ]);
    }
}
