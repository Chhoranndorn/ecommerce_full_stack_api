<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Get shareable profile data
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        // Generate shareable content
        $shareText = "Check out {$user->name}'s profile on our app!";

        // You can customize the URL based on your app's deep linking setup
        $shareUrl = config('app.url') . '/profile/' . $user->id;

        return response()->json([
            'success' => true,
            'data' => [
                'share_text' => $shareText,
                'share_url' => $shareUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_picture' => $user->profile_picture,
                ],
            ],
        ]);
    }

    /**
     * Get shareable product data
     */
    public function product(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);

        $shareText = "Check out this product: {$product->name}";
        $shareUrl = config('app.url') . '/products/' . $product->id;

        return response()->json([
            'success' => true,
            'data' => [
                'share_text' => $shareText,
                'share_url' => $shareUrl,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ],
            ],
        ]);
    }

    /**
     * Get app referral/invite link
     */
    public function referral(Request $request)
    {
        $user = $request->user();

        // Generate unique referral code (you can customize this)
        $referralCode = 'REF' . $user->id . strtoupper(substr(md5($user->email), 0, 6));

        $shareText = "Join me on this amazing shopping app! Use my referral code: {$referralCode}";
        $shareUrl = config('app.url') . '/invite/' . $referralCode;

        return response()->json([
            'success' => true,
            'data' => [
                'referral_code' => $referralCode,
                'share_text' => $shareText,
                'share_url' => $shareUrl,
            ],
        ]);
    }
}
