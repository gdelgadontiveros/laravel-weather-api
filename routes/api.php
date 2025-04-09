<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Weather\WeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('location', [UserController::class, 'updateLocation']);
    });
    
    // Weather routes
    Route::prefix('weather')->group(function () {
        Route::get('current', [WeatherController::class, 'current']);
        Route::get('forecast', [WeatherController::class, 'forecast']);
    });
});
