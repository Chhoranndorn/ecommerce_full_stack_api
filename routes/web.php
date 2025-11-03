<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;

// Frontend Website Routes
Route::get('/', [WebController::class, 'index']);
Route::get('/products', [WebController::class, 'products']);
Route::get('/products/{id}', [WebController::class, 'productDetail']);
Route::get('/about', [WebController::class, 'about']);
Route::get('/contact', [WebController::class, 'contact']);