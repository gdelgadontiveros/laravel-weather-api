<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\API\V1\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// // Rutas públicas
// Route::prefix('v1')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);


//     // Ruta de ejemplo para mostrar la estructura de una respuesta de la API
//     Route::get('/example-api-response', [InvoiceController::class, 'getExampleApiResponse']);
//     // Fin ejemplo para mostrar la estructura de una respuesta de la API
// });

// // Rutas protegidas
// Route::middleware(['auth:sanctum', RefreshPermissionCache::class])->prefix('v1')->group( function () {

// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
