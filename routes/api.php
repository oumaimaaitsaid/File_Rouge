<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\Admin\ProductController as ProductAdminController;
use App\Http\Controllers\API\Admin\ReviewController as ReviewAdminController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/recent', [ProductController::class, 'recent']);
    Route::get('/products/search/{query}', [ProductController::class, 'search']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    //categorie
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    //verification de code_promo
    Route::post('/promo/validate', [App\Http\Controllers\API\PromotionController::class, 'validerCode']);
    
    
});
Route::prefix('v1')->middleware('auth:sanctum')->group(function() {
    //cart
    Route::get('/cart', [App\Http\Controllers\API\CartController::class, 'index']);
    Route::post('/cart/add', [App\Http\Controllers\API\CartController::class, 'addItem']);
    Route::put('/cart/update', [App\Http\Controllers\API\CartController::class, 'updateItem']);
    Route::delete('/cart/remove/{id}', [App\Http\Controllers\API\CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [App\Http\Controllers\API\CartController::class, 'clear']);
    // Afficher les avis de l'utilisateur
    //reviews
    Route::get('/user/reviews', [ReviewController::class, 'userReviews']);
    
    // Ajouter un avis
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);
    
    // Mettre à jour un avis
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    
    // Supprimer un avis
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
Route::put('/profile', [App\Http\Controllers\API\UserController::class, 'updateProfile']);





 Route::get('/checkout/validate-cart', [App\Http\Controllers\API\CheckoutController::class, 'validateCart']);
    
    // Créer une commande
    Route::post('/checkout/create-order', [App\Http\Controllers\API\CheckoutController::class, 'createOrder']);
    
    // Confirmer le paiement d'une commande
    Route::post('/checkout/confirm-payment/{orderId}', [App\Http\Controllers\API\CheckoutController::class, 'confirmPayment']);
    
    // Obtenir l'historique des commandes
    Route::get('/orders', [App\Http\Controllers\API\CheckoutController::class, 'userOrders']);
    
    // Obtenir les détails d'une commande
    Route::get('/orders/{orderId}', [App\Http\Controllers\API\CheckoutController::class, 'orderDetails']);
    
    // Annuler une commande
    Route::post('/orders/{orderId}/cancel', [App\Http\Controllers\API\CheckoutController::class, 'cancelOrder']);
});


Route::prefix('v1')->middleware(['auth:sanctum'])->group(function(){
    Route::get('/user',function(Request $request){
        return response()->json([
            'success'=>true,
            'data'=>$request->user()

        ]);
    });

   

    Route::post('/logout',[AuthController::class,'logout']);
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Vous avez accès en tant qu\'admin',
            ]);
        });
        Route::apiResource('/products', ProductAdminController::class);
        Route::post('/products/{id}/images', [ProductAdminController::class, 'uploadImages']);
        Route::delete('/products/{id}/images/{imageId}', [ProductAdminController::class, 'deleteImage']);
        Route::apiResource('/categories', App\Http\Controllers\API\Admin\CategoryController::class);
        Route::get('/reviews', [ReviewAdminController::class, 'index']);
        Route::get('/reviews/{id}', [ReviewAdminController::class, 'show']);
        Route::put('/reviews/{id}', [ReviewAdminController::class, 'update']);
        Route::delete('/reviews/{id}', [ReviewAdminController::class, 'destroy']);
        Route::apiResource('/users', App\Http\Controllers\API\Admin\UserController::class);
        Route::get('/orders', [App\Http\Controllers\API\Admin\OrderController::class, 'index']);
        Route::get('/orders/{id}', [App\Http\Controllers\API\Admin\OrderController::class, 'show']);
        Route::put('/orders/{id}/status', [App\Http\Controllers\API\Admin\OrderController::class, 'updateStatus']);
        Route::put('/orders/{id}/confirm-payment', [App\Http\Controllers\API\Admin\OrderController::class, 'confirmPayment']);
        Route::get('/orders/statistics/summary', [App\Http\Controllers\API\Admin\OrderController::class, 'statistics']);

         // Tableau de bord général
        Route::get('/dashboard', [App\Http\Controllers\API\Admin\DashboardController::class, 'index']);
    
    // Statistiques des ventes
       Route::get('/dashboard/sales', [App\Http\Controllers\API\Admin\DashboardController::class, 'salesStats']);
    
    // Statistiques des produits
    Route::get('/dashboard/products', [App\Http\Controllers\API\Admin\DashboardController::class, 'productStats']);
    
    // Statistiques des clients
    Route::get('/dashboard/customers', [App\Http\Controllers\API\Admin\DashboardController::class, 'customerStats']);

     // CRUD des promotions
     Route::apiResource('/promotions', App\Http\Controllers\API\Admin\PromotionController::class);
    
     // Activer/désactiver une promotion
     Route::patch('/promotions/{id}/toggle-active', [App\Http\Controllers\API\Admin\PromotionController::class, 'toggleActive']);
    
    });
    Route::middleware('partenaire')->prefix('partner')->group(function () {
        Route::get('/test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Vous avez accès en tant que partenaire',
            ]);
        });
    });

});