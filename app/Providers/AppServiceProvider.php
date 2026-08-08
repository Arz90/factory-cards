<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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

        // Forzar HTTPS solo cuando la petición llega a través de un proxy o túnel externo
        // (Cloudflare Tunnel, ngrok, etc.) que añade la cabecera X-Forwarded-Proto: https.
        // En navegación local directa (http://127.0.0.1:8000) NO se activa, evitando
        // que asset() y route() generen URLs https:// que el servidor local no puede servir.
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
