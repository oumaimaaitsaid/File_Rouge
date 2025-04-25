<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPromotionController;
use App\Http\Controllers\Admin\AdminReviewController;

//routes pour  l admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Tableau de bord
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des produits
    Route::resource('products', AdminProductController::class);
    
    // Gestion des catégories
    Route::resource('categories', AdminCategoryController::class);
    
    // Gestion des commandes
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store', 'destroy']);
    Route::post('orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{id}/update-payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    
    // Gestion des utilisateurs
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);
    
    // Gestion des promotions
    Route::resource('promotions', AdminPromotionController::class);
    
    // Gestion des avis
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{id}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Statistiques
    Route::get('statistics', [AdminDashboardController::class, 'statistics'])->name('statistics');
    Route::get('statistics/sales', [AdminDashboardController::class, 'salesStatistics'])->name('statistics.sales');
    Route::get('statistics/products', [AdminDashboardController::class, 'productStatistics'])->name('statistics.products');
    Route::get('statistics/users', [AdminDashboardController::class, 'userStatistics'])->name('statistics.users');
});
// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    
    // Route pour soumettre un nouvel avis sur un produit
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->name('products.review');
    
     // Routes pour les avis de l'utilisateur connecté
     Route::get('/user/reviews', [ReviewController::class, 'userReviews'])->name('user.reviews');
     Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
     Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
     Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
     Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
     Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
     Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');


     
     // Routes pour les commandes
     Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
     Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');
     Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
     
     
    });
  
   

// Routes des produits
Route::get('/catalog', [ProductController::class, 'catalog'])->name('products.catalog');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
// Route pour la page d'accueil
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

//Routes des promotions
Route::post('/promotions/validate', [PromotionController::class, 'validerCode'])->name('promotions.validate');
Route::delete('/promotions/remove', [PromotionController::class, 'supprimerCode'])->name('promotions.remove');
// Routes principales
Route::get('/', function () {
    // Récupérer les données nécessaires pour la page d'accueil
    $featuredProducts = \App\Models\Produit::where('featured', true)
        ->where('disponible', true)
        ->with(['imagePrincipale', 'categorie'])
        ->take(4)
        ->get();
        
    $recentProducts = \App\Models\Produit::where('disponible', true)
        ->with(['imagePrincipale', 'categorie'])
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();
        
    $categories = \App\Models\Categorie::where('active', true)->take(4)->get();
    
    return view('home', compact('featuredProducts', 'recentProducts', 'categories'));
})->name('home');

// Routes pour les pages statiques
Route::get('/contact', function () {
    return view('statics.contact');
})->name('contact');
  
Route::get('/about', function () {
    return view('statics.about');
})->name('about');
Route::get('/terms', function () {
    return view('statics.terms');
})->name('terms');
Route::get('/privacy', function () {
    return view('statics.privacy');
})->name('privacy');
Route::get('/faq', function () {
    return view('statics.faq');
})->name('faq');

Route::get('/cart/info', [CartController::class, 'getCartInfo'])->name('cart.info');


//payement 

// Routes pour Stripe
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/stripe/{orderId}', [PaymentController::class, 'processCardPayment'])->name('payment.stripe.process');
    Route::get('/payment/stripe/success/{orderId}', [PaymentController::class, 'handleStripeSuccess'])->name('payment.stripe.success');
    Route::get('/payment/stripe/cancel/{orderId}', [PaymentController::class, 'handleStripeCancel'])->name('payment.stripe.cancel');
    Route::get('/payment/card/{orderId}', [PaymentController::class, 'showCardForm'])->name('payment.card');
    Route::post('/payment/confirm/{orderId}', [PaymentController::class, 'confirm'])->name('payment.confirm');
});