# Instalación — Factory Cards

## 1. Requisitos previos
- PHP 8.2+ (recomendado: [Laravel Herd](https://herd.laravel.com/) en Windows)
- Composer
- MySQL 8+
- Node.js 20+

## 2. Crear el proyecto Laravel base

Desde la carpeta raíz del repo, ejecuta:

```bash
# Crear proyecto Laravel 11 en la carpeta actual
composer create-project laravel/laravel . "^11.0"

# Instalar Breeze (auth con Blade)
composer require laravel/breeze --dev
php artisan breeze:install blade

# Instalar dependencias Node
npm install && npm run build
```

## 3. Configurar .env

Copia `.env.example` a `.env` y edita:

```env
APP_NAME="Factory Cards"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=factory_cards
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

## 4. Registrar middleware y provider

En `bootstrap/app.php`, registrar el middleware de admin:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})
```

En `config/app.php`, registrar el ViewServiceProvider (si no está en bootstrap):

```php
// O añadir en bootstrap/providers.php:
App\Providers\ViewServiceProvider::class,
```

## 5. Base de datos y seed

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 6. Arrancar el servidor

```bash
php artisan serve
```

Accede a `http://localhost:8000`.

**Credenciales de demo:**
- Admin: `admin@factorycards.es` / `password`
- Cliente: `cliente@ejemplo.es` / `password`
