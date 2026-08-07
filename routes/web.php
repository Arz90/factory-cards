<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FranchiseController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas - Tienda
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/tienda', [ShopController::class, 'catalog'])->name('shop.catalog');
Route::get('/producto/{slug}', [ShopController::class, 'show'])->name('shop.product');
Route::get('/franquicia/{slug}', [ShopController::class, 'byFranchise'])->name('shop.franchise');
Route::get('/categoria/{slug}', [ShopController::class, 'byCategory'])->name('shop.category');
Route::get('/buscar', [ShopController::class, 'search'])->name('shop.search');

/*
|--------------------------------------------------------------------------
| Carrito de Compra
|--------------------------------------------------------------------------
*/

Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/anadir/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/actualizar/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/eliminar/{cartItem}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/vaciar', [CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/procesar', [CheckoutController::class, 'process'])->name('process');
    Route::get('/confirmacion/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
    // Webhooks de pago (no protegidos por CSRF)
    Route::post('/webhook/stripe', [CheckoutController::class, 'stripeWebhook'])->name('webhook.stripe');
    Route::post('/webhook/redsys', [CheckoutController::class, 'redsysWebhook'])->name('webhook.redsys');
});

/*
|--------------------------------------------------------------------------
| Panel de Usuario (requiere auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('mi-cuenta')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/pedidos', [UserController::class, 'orders'])->name('orders');
    Route::get('/pedidos/{order}', [UserController::class, 'orderDetail'])->name('order.detail');
    Route::get('/perfil', [UserController::class, 'profile'])->name('profile');
    Route::put('/perfil', [UserController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Panel de Administración (requiere auth + rol admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::resource('productos', ProductController::class);
    Route::patch('productos/{product}/stock', [ProductController::class, 'updateStock'])->name('productos.stock');
    Route::patch('productos/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('productos.featured');

    // Categorías
    Route::resource('categorias', CategoryController::class);

    // Franquicias
    Route::resource('franquicias', FranchiseController::class);

    // Pedidos
    Route::get('pedidos', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('pedidos/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('pedidos/{order}/estado', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze/UI las registra automáticamente)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
