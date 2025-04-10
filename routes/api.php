<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\User\UserController;
use App\Http\Controllers\API\V1\Weather\WeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User routes
    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('location', [UserController::class, 'updateLocation']);
        Route::get('weather-history', [UserController::class, 'weatherHistory']);
    });

    // Weather routes
    Route::prefix('weather')->group(function () {
        Route::get('current', [WeatherController::class, 'current']);
        Route::get('forecast', [WeatherController::class, 'forecast']);
    });
});
