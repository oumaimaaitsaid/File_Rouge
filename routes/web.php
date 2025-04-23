<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PaymentController;
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
     Route::post('/checkout/{orderId}/confirm-payment', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm-payment');
     
     // Routes pour les commandes
     Route::get('/orders', [CheckoutController::class, 'userOrders'])->name('orders.index');
     Route::get('/orders/{orderId}', [CheckoutController::class, 'orderDetails'])->name('orders.show');
     Route::post('/orders/{orderId}/cancel', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');
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
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/cart/info', [CartController::class, 'getCartInfo'])->name('cart.info');


//payement 

// Routes pour Stripe
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/stripe/{orderId}', [PaymentController::class, 'processCardPayment'])->name('payment.stripe.process');
    Route::get('/payment/stripe/success/{orderId}', [PaymentController::class, 'handleStripeSuccess'])->name('payment.stripe.success');
    Route::get('/payment/stripe/cancel/{orderId}', [PaymentController::class, 'handleStripeCancel'])->name('payment.stripe.cancel');
});
Route::get('/payment/card/{orderId}', [PaymentController::class, 'showCardForm'])->name('payment.card');