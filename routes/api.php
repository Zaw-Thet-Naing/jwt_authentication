<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\ArticlesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;

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

Route::middleware(["api"])->group(function() {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);

    Route::get('/ads' , [AdsController::class, 'index']);
    Route::post('/ads' , [AdsController::class , 'create']);
    Route::put('/ads/{id}' , [AdsController::class, 'update']);
    Route::delete('/ads/{id}' , [AdsController::class, 'destory']);

    Route::get('/articles' , [ArticlesController::class, 'index']);
    Route::post('/articles' , [ArticlesController::class, 'create']);
    Route::put('articles' , [ArticlesController::class , 'update']);
    Route::delete('articles' , [ArticlesController::class , 'destory']);
});