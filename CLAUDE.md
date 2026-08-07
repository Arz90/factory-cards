# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project: Factory Cards

Tienda online TCG (Pokémon, MTG, One Piece, etc.) construida en Laravel 11 + MySQL. Sustituye un sistema de terceros para eliminar comisiones externas.

## Commands

```bash
# Arrancar servidor de desarrollo
php artisan serve

# Migraciones y seed inicial
php artisan migrate
php artisan db:seed

# Reset completo de BD (desarrollo)
php artisan migrate:fresh --seed

# Build de assets
npm run dev        # watch mode
npm run build      # producción

# Crear controlador de recurso admin
php artisan make:controller Admin/NombreController --resource

# Limpiar cachés
php artisan cache:clear && php artisan view:clear && php artisan config:clear
```

## Stack

- **Backend**: Laravel 11, PHP 8.2+, MySQL 8
- **Frontend**: Blade Templates, Bootstrap 5 (CDN), Bootstrap Icons (CDN), Vanilla JS
- **Auth**: Laravel Breeze (Blade)
- **Pagos**: Stripe + Redsys (TPV Virtual)
- **Sin Vite/mix para CSS/JS propios**: los assets están en `public/css/app.css` y `public/js/app.js` servidos directamente

## Architecture

### Database schema (orden de dependencias)
```
users → (rol: admin | customer)
franchises → (Pokémon, MTG, etc. — con icon, color, sort_order)
categories → (auto-referencia parent_id para subcategorías)
products → (category_id, franchise_id, softDeletes, json gallery/attributes)
orders → (user_id nullable para guest checkout, order_number FC-YYYY-NNNNN)
order_items → (snapshot de nombre/precio en el momento de compra)
cart_items → (user_id O session_id, fusión al hacer login)
```

### Roles y acceso
- `User::isAdmin()` / `User::isCustomer()` — helpers en el modelo
- Middleware `admin` → `App\Http\Middleware\EnsureUserIsAdmin` — registrado como alias
- Rutas admin: prefijo `/admin`, middleware `['auth', 'admin']`
- Rutas usuario: prefijo `/mi-cuenta`, middleware `['auth']`
- Guest checkout: el campo `user_id` en `orders` es nullable

### Datos globales en vistas (header/footer)
`App\Providers\ViewServiceProvider` inyecta en todas las vistas:
- `$headerCategories` — categorías raíz activas con hijos (cacheado 5 min)
- `$headerFranchises` — franquicias activas ordenadas (cacheado 5 min)
- `$cartCount` — desde DB (auth) o sesión (guest)

Invalidar caché de header: `cache()->forget('header_categories')` y `cache()->forget('header_franchises')`.

### Layout: `resources/views/layouts/app.blade.php`
Header de 3 niveles:
1. **Topbar** (desktop): buscador con selector de categorías + links de cuenta
2. **Navbar** (Bootstrap, sticky): logo + menú + carrito
3. **Franchise bar**: iconos/logos de franquicias clickables

Offcanvas lateral para mobile con buscador + navegación completa.
Trust badges debajo del header (envío, pago, devoluciones, soporte).

### API interna (`routes/api.php`, prefijo `/api/v1`)
- `GET /api/v1/products` — lista paginada, filtros: `category`, `franchise`, `q` (búsqueda), `featured`, `status`
- `GET /api/v1/products/{slug}` — detalle de producto
- `GET /api/v1/categories` y `/api/v1/franchises` — para poblar selects del frontend

### Product model helpers
- `hasDiscount()`, `discountPercentage()` — para mostrar precio tachado
- `isAvailable()` — stock > 0 y status en [active, preorder]
- `badgeLabel()` → `"OFERTA"` | `"PRECOMPRA"` | null
- `badgeColor()` → clase Bootstrap (`danger` | `primary`)

### Order model
- `order_number` se genera automáticamente en el boot: `FC-YYYY-NNNNN`
- `statusLabel()` y `statusColor()` — para mostrar estado en español con color Bootstrap

### Carrito
- Usuarios autenticados: `cart_items` en BD (`user_id`)
- Guests: sesión (clave `cart`) + `session_id` en `cart_items`
- Al hacer login: fusionar carrito de sesión con el de BD

### Flujo de pago
1. Checkout crea `Order` con `status=pending`
2. Redirige a Stripe/Redsys
3. Webhook confirma el pago → `status=paid`, `paid_at=now()`, descuento de stock automático
4. Rutas de webhook EXCLUIDAS del middleware CSRF (configurar en `bootstrap/app.php`)

## Key conventions
- Slugs siempre en español/kebab-case (generados con `Str::slug()`)
- Precios siempre `decimal(10,2)` en BD; formatear con `number_format($price, 2, ',', '.')` en Blade
- Imágenes de productos: `public/images/products/` — acceso via `asset('images/products/...')`
- Imágenes de franquicias: `public/images/franchises/`
- Soft deletes en `products` — nunca borrar físicamente un producto con pedidos
- Los `order_items` guardan snapshot de `product_name` y `price` para preservar historial
