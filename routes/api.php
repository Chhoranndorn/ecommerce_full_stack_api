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
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\DeliveryMethodController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\SpecialController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AppSettingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\ShareController;

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

// About us route
Route::get('/about-us', [AppSettingController::class, 'getAboutUs']);

// Onboarding screens route
Route::get('/onboarding', [OnboardingController::class, 'index']);

// Languages route
Route::get('/languages', [LanguageController::class, 'index']);

// Delivery methods route
Route::get('/delivery-methods', [DeliveryMethodController::class, 'index']);
Route::get('/delivery-methods/{id}', [DeliveryMethodController::class, 'show']);

// Specials/Promotions route
Route::get('/specials', [SpecialController::class, 'index']);
Route::get('/specials/{id}', [SpecialController::class, 'show']);

// User Profile routes (Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);
    Route::put('/user/language', [UserProfileController::class, 'updateLanguage']);
    Route::put('/user/password', [UserProfileController::class, 'changePassword']);
    Route::post('/user/notifications/toggle', [UserProfileController::class, 'toggleNotifications']);

    // Profile screen with stats (comprehensive)
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);

    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::post('/cart/clear', [CartController::class, 'clear']);
    Route::post('/cart/{id}/increment', [CartController::class, 'increment']);
    Route::post('/cart/{id}/decrement', [CartController::class, 'decrement']);

    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Quote routes (Quotation - សម្រង់តម្លៃ)
    Route::get('/quotes', [QuoteController::class, 'index']);
    Route::get('/quotes/{id}', [QuoteController::class, 'show']);
    Route::post('/quotes', [QuoteController::class, 'store']);
    Route::put('/quotes/{id}', [QuoteController::class, 'update']);
    Route::delete('/quotes/{id}', [QuoteController::class, 'destroy']);
    Route::post('/quotes/{id}/convert-to-order', [QuoteController::class, 'convertToOrder']);

    // Address routes (អាសយដ្ឋាន)
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::get('/addresses/default', [AddressController::class, 'getDefault']);
    Route::get('/addresses/{id}', [AddressController::class, 'show']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::post('/addresses/{id}/set-default', [AddressController::class, 'setDefault']);

    // Coupon routes
    Route::post('/coupons/verify', [CouponController::class, 'verify']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/notifications/{id}', [NotificationController::class, 'show']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // Wallet routes
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // Points routes
    Route::get('/points/transactions', [PointController::class, 'transactions']);
    Route::get('/points/earned', [PointController::class, 'earned']);
    Route::get('/points/withdrawn', [PointController::class, 'withdrawn']);
    Route::post('/points/convert', [PointController::class, 'convert']);

    // Feedback routes
    Route::post('/feedback', [FeedbackController::class, 'submit']);
    Route::get('/feedback', [FeedbackController::class, 'index']);

    // Share routes
    Route::get('/share/profile', [ShareController::class, 'profile']);
    Route::get('/share/product/{productId}', [ShareController::class, 'product']);
    Route::get('/share/referral', [ShareController::class, 'referral']);
});
