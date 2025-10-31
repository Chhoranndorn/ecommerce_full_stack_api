<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Login
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    // Send OTP to phone
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        // Generate 6-digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete old OTPs for this phone
        Otp::where('phone', $request->phone)->delete();

        // Create new OTP
        $otp = Otp::create([
            'phone' => $request->phone,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(5),
            'is_verified' => false,
            'attempts' => 0,
        ]);

        // TODO: Send SMS with OTP code
        // For development, return OTP in response
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otpCode, // Remove this in production
            'expires_in' => 300, // seconds
        ]);
    }

    // Verify OTP and login/register
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string|size:6',
        ]);

        $otp = Otp::where('phone', $request->phone)
            ->where('otp_code', $request->otp_code)
            ->where('is_verified', false)
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 401);
        }

        if ($otp->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired',
            ], 401);
        }

        // Mark OTP as verified
        $otp->update(['is_verified' => true]);

        // Find or create user
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'phone' => $request->phone,
                'name' => $request->name ?? 'User',
                'phone_verified' => true,
            ]);
        } else {
            // Update phone verification
            $user->update(['phone_verified' => true]);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Social Login (Google/Apple)
    public function socialLogin(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:google,apple',
            'provider_id' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'required|string',
        ]);

        $provider = $request->provider;
        $providerId = $request->provider_id;

        // Find user by provider ID
        $user = User::where($provider . '_id', $providerId)->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                $provider . '_id' => $providerId,
            ]);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
