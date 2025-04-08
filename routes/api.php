<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\PromoCodeController;
use App\Http\Controllers\API\Admin\DashboardController;
use App\Http\Controllers\API\Admin\ProductController as AdminProductController;
use App\Http\Controllers\API\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\API\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\API\Admin\PromotionController as AdminPromotionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/recent', [ProductController::class, 'recent']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/products/search/{query}', [ProductController::class, 'search']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/products', [CategoryController::class, 'products']);
    
    // Reviews
    Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
    
    // Promo Codes
    Route::post('/promo-codes/verify', [PromoCodeController::class, 'verify']);
});

// Routes protégées (nécessitant authentification)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', [UserController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User profile
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::put('/password', [UserController::class, 'changePassword']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    
    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update', [CartController::class, 'update']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    
    // Reviews
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
    
    // Checkout
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/payment/{orderId}', [OrderController::class, 'processPayment']);
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/stats', [DashboardController::class, 'stats']);
        
        // Products management
        Route::apiResource('/products', AdminProductController::class);
        Route::post('/products/{id}/images', [AdminProductController::class, 'uploadImages']);
        Route::delete('/products/{id}/images/{imageId}', [AdminProductController::class, 'deleteImage']);
        
        // Categories management
        Route::apiResource('/categories', AdminCategoryController::class);
        
        // Orders management
        Route::apiResource('/orders', AdminOrderController::class)->except(['store', 'destroy']);
        Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
        Route::get('/orders/export', [AdminOrderController::class, 'export']);
        
        // Users management
        Route::apiResource('/users', AdminUserController::class);
        
        // Reviews management
        Route::apiResource('/reviews', AdminReviewController::class)->except(['store']);
        Route::put('/reviews/{id}/approve', [AdminReviewController::class, 'approve']);
        
        // Promo codes management
        Route::apiResource('/promotions', AdminPromotionController::class);
    });
    
    // Partner routes
    Route::middleware('partenaire')->prefix('partner')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'partnerDashboard']);
        Route::get('/orders', [OrderController::class, 'partnerOrders']);
        Route::post('/orders', [OrderController::class, 'partnerPlaceOrder']);
    });
});