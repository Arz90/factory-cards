<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

/*
|--------------------------------------------------------------------------
| API REST interna para carga dinámica de productos
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Listar productos con filtros (categoría, franquicia, búsqueda, paginación)
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{slug}', [ProductApiController::class, 'show']);

    // Endpoints de soporte para filtros del frontend
    Route::get('/categories', [ProductApiController::class, 'categories']);
    Route::get('/franchises', [ProductApiController::class, 'franchises']);
});
