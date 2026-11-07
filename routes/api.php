<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\PaymentController;

// PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/product-photos', [ProductPhotoController::class, 'index']);
Route::get('/product-photos/{product_photo}', [ProductPhotoController::class, 'show']);

// PRIVATE (any logged-in user)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('carts', CartController::class);
    Route::apiResource('cart-products', CartProductController::class);

    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-products', OrderProductController::class);
    Route::apiResource('payments', PaymentController::class);

    Route::post('/product-reviews', [ProductReviewController::class, 'store']);
    Route::put('/product-reviews/{product_review}', [ProductReviewController::class, 'update']);
    Route::delete('/product-reviews/{product_review}', [ProductReviewController::class, 'destroy']);

    // PRIVATE + ADMIN ONLY (nested, so auth runs first)
    Route::middleware('admin')->group(function () {
        Route::apiResource('products', ProductController::class)
            ->except(['index', 'show']);

        Route::apiResource('categories', CategoryController::class)
            ->except(['index', 'show']);

        Route::apiResource('product-photos', ProductPhotoController::class)
        
            ->except(['index', 'show']);
 
    });
});