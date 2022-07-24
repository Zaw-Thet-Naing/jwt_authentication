<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MagazineController;

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
    Route::post('/register', [JWTController::class, 'register'])->name("register");
    Route::post('/login', [JWTController::class, 'login'])->name("login");
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);
});

Route::controller(EventController::class)->group(function() {
    Route::get("events", "index");
    Route::get("events/{id}", "show");
    Route::post("events", "create");
    Route::put("events/{id}", "update");
    Route::delete("events/{id}", "destroy");
});

Route::controller(MagazineController::class)->group(function() {
    Route::get("magazines", "index");
    Route::get("magazines/{id}", "show");
    Route::post("magazines", "create");
    Route::put("magazines/{id}", "update");
    Route::delete("magazines/{id}", "destroy");
});