<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AppConfigController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WishlistController;

// Register & Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Phone Login with OTP
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Social Login
Route::post('/social-login', [AuthController::class, 'socialLogin']);

// Example test route
Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
});

// Example user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

// Categories routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Products routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/new/latest', [ProductController::class, 'newProducts']);

// Search route
Route::get('/search', [ProductController::class, 'search']);

// App configuration/settings route
Route::get('/config', [AppConfigController::class, 'index']);

// Onboarding screens route
Route::get('/onboarding', [OnboardingController::class, 'index']);

// Languages route
Route::get('/languages', [LanguageController::class, 'index']);

// User Profile routes (Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);
    Route::put('/user/language', [UserProfileController::class, 'updateLanguage']);
    Route::put('/user/password', [UserProfileController::class, 'changePassword']);
    Route::post('/user/notifications/toggle', [UserProfileController::class, 'toggleNotifications']);

    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);
});
