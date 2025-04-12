<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Categorie;

use App\Http\Controllers\API\Admin\ProductController as ProductAdminController;


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