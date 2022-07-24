<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\BannerSliderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\ShopCategoriesController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Show
Route::get('/ads' , [AdsController::class, 'index']);
Route::get('/articles' , [ArticlesController::class, 'index']);
Route::get('/banner' , [BannerSliderController::class, 'index']);
Route::get('/shopCategories', [ShopCategoriesController::class, 'index']);
Route::get('/shops', [ShopController::class, 'index']);

// Get vlaues with id
Route::get('/shopCategories/{id}', [ShopCategoriesController::class, 'show']);
Route::get('/shops/{id}', [ShopController::class, 'show']);
Route::get('/ads/{id}', [AdsController::class, 'show']);
Route::get('/articles/{id}', [ArticlesController::class, 'show']);

Route::middleware(["api"])->group(function(){
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);
    
    Route::post('/ads' , [AdsController::class , 'create']);
    Route::put('/ads/{id}' , [AdsController::class, 'update']);
    Route::delete('/ads/{id}' , [AdsController::class, 'destory']);

    Route::post('/articles' , [ArticlesController::class, 'create']);
    Route::put('articles/{id}' , [ArticlesController::class , 'update']);
    Route::delete('articles/{id}' , [ArticlesController::class , 'destory']);

    Route::post('/banner' , [BannerSliderController::class, 'create']);
    Route::put('banner/{id}' , [BannerSliderController::class , 'update']);
    Route::delete('banner/{id}' , [BannerSliderController::class , 'destory']);

    Route::post('/shopCategories', [ShopCategoriesController::class, 'store']);
    Route::put('/shopCategories/{id}', [ShopCategoriesController::class, 'update']);
    Route::delete('/shopCategories/{id}', [ShopCategoriesController::class, 'destroy']);

    Route::post('/shops', [ShopController::class, 'store']);
    Route::put('/shops/{id}', [ShopController::class, 'update']);
    Route::delete('/shops/{id}', [ShopController::class, 'destroy']);
});