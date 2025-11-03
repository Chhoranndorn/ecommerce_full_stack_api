<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Get user profile
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'phone_verified' => $user->phone_verified,
                'whatsapp' => $user->whatsapp,
                'telegram' => $user->telegram,
                'wechat' => $user->wechat,
                'address' => $user->address,
                'profile_picture' => $user->profile_picture,
                'language' => $user->language ?? 'km',
                'notifications_enabled' => $user->notifications_enabled ?? true,
                'created_at' => $user->created_at,
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
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|unique:users,phone,' . $user->id,
            'whatsapp' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'wechat' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|string',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone',
            'whatsapp',
            'telegram',
            'wechat',
            'address',
            'profile_picture'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'whatsapp' => $user->whatsapp,
                'telegram' => $user->telegram,
                'wechat' => $user->wechat,
                'address' => $user->address,
                'profile_picture' => $user->profile_picture,
            ],
        ]);
    }

    /**
     * Update language preference
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:km,en',
        ]);

        $user = $request->user();
        $user->update(['language' => $request->language]);

        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'data' => [
                'language' => $user->language,
            ],
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Toggle notifications
     */
    public function toggleNotifications(Request $request)
    {
        $user = $request->user();
        $enabled = !($user->notifications_enabled ?? true);

        $user->update(['notifications_enabled' => $enabled]);

        return response()->json([
            'success' => true,
            'message' => $enabled ? 'Notifications enabled' : 'Notifications disabled',
            'data' => [
                'notifications_enabled' => $enabled,
            ],
        ]);
    }
}
