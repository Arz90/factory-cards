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
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PromoBannerController;
use App\Http\Controllers\Admin\SingleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SellCardsController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas - Tienda
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/tienda', [ShopController::class, 'catalog'])->name('shop.catalog');
Route::get('/eventos', [EventController::class, 'index'])->name('events.index');
Route::get('/producto/{slug}', [ShopController::class, 'show'])->name('shop.product');
Route::get('/franquicia/{slug}', [ShopController::class, 'byFranchise'])->name('shop.franchise');
Route::get('/categoria/{slug}', [ShopController::class, 'byCategory'])->name('shop.category');
Route::get('/buscar', [ShopController::class, 'search'])->name('shop.search');

// Vende tus cartas — landing page de captación de stock de segunda mano
Route::get('/vende-tus-cartas', [SellCardsController::class, 'index'])->name('sell-cards.index');
Route::post('/vende-tus-cartas', [SellCardsController::class, 'store'])->name('sell-cards.store');

/*
|--------------------------------------------------------------------------
| Chatbot (AJAX — sin autenticación requerida)
|--------------------------------------------------------------------------
*/
Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/eventos',   [ChatbotController::class, 'eventosProximos'])->name('eventos');
    Route::post('/contacto', [ChatbotController::class, 'enviarMensaje'])->name('contacto');
});

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

/*
|--------------------------------------------------------------------------
| Lista de Deseos / Wishlist (requiere auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('deseos')->name('wishlist.')->group(function () {
    Route::get('/',                         [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle/{product}',        [WishlistController::class, 'toggle'])->name('toggle');
});

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

    // Banners (hero slider de la portada)
    Route::resource('banners', BannerController::class);
    Route::patch('banners/{banner}/toggle', [BannerController::class, 'toggleActivo'])->name('banners.toggle');

    // Eventos y torneos
    Route::resource('eventos', AdminEventController::class);

    // Singles — panel de gestión y sincronización con API Pokémon TCG
    Route::get('singles', [SingleController::class, 'index'])->name('singles.index');
    Route::post('singles/sync', [SingleController::class, 'sync'])->name('singles.sync');

    // Promo Banners (sección destacada de la portada)
    Route::resource('promo-banners', PromoBannerController::class);
    Route::patch('promo-banners/{promoBanner}/toggle', [PromoBannerController::class, 'toggleActivo'])->name('promo-banners.toggle');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze/UI las registra automáticamente)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
