<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usar el template de paginación Bootstrap 5 en lugar del Tailwind por defecto.
        // Sin esto, Laravel renderiza SVG de Tailwind que aparecen enormes al no tener
        // las clases utilitarias w-5/h-5 de Tailwind cargadas.
        Paginator::useBootstrapFive();
    }
}
