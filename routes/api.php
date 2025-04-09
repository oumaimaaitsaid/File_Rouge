<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
  
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function(){
    route::get('/user',function(Request $request){
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
                'message' => 'Vous avez accès en tant qu\'admin'
            ]);
        });
    });
    Route::middleware('partenaire')->prefix('partner')->group(function () {
        Route::get('/test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Vous avez accès en tant que partenaire'
            ]);
        });
    });

});