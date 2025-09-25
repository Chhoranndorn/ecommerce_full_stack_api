<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Register & Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Example test route
Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
});

// Example user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
