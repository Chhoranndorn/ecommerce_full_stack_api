<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get complete profile data for profile screen
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get counts for quick stats
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $cartItemsCount = Cart::where('user_id', $user->id)->count();
        $unreadNotificationsCount = Notification::forUser($user->id)->unread()->count();
        $ordersCount = Order::where('user_id', $user->id)->count();

        // Calculate points/balance (you can customize this logic)
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->sum('total');

        $points = floor($totalSpent); // 1 point per $1 spent (customize as needed)

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_picture' => $user->profile_picture ?? null,
                    'language' => $user->language ?? 'km',
                ],
                'balance' => [
                    'points' => $points,
                    'currency' => '$',
                    'amount' => number_format($totalSpent, 2),
                ],
                'stats' => [
                    'wishlist_count' => $wishlistCount,
                    'cart_count' => $cartItemsCount,
                    'notifications_count' => $unreadNotificationsCount,
                    'orders_count' => $ordersCount,
                ],
            ],
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|string',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('profile_picture')) {
            $user->profile_picture = $request->profile_picture;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_picture' => $user->profile_picture,
            ],
        ]);
    }
}
