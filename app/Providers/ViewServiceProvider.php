<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Franchise;
use App\Models\CartItem;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Comparte datos globales con todas las vistas (header, footer).
     * Se cachean para evitar consultas repetidas en cada request.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Solo si las tablas ya existen (evita errores durante migraciones)
            try {
                $view->with([
                    'headerCategories' => cache()->remember('header_categories', 300, fn() =>
                        Category::active()->roots()->with('children')->get()
                    ),
                    'headerFranchises' => cache()->remember('header_franchises', 300, fn() =>
                        Franchise::active()->get()
                    ),
                    'cartCount' => auth()->check()
                        ? auth()->user()->cartItems()->sum('quantity')
                        : (int) session('cart_count', 0),
                    'cartTotal' => auth()->check()
                        ? CartItem::with('product')->where('user_id', auth()->id())->get()->sum(fn($i) => $i->lineTotal())
                        : collect(session('cart', []))->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0)),
                ]);
            } catch (\Exception $e) {
                // Silencioso durante setup inicial
            }
        });
    }
}
