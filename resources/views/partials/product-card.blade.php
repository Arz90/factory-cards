{{--
    ════════════════════════════════════════════════════════════════
    PARTIAL: partials/product-card
    ════════════════════════════════════════════════════════════════
    Tarjeta de producto reutilizable — usada en portada, catálogo,
    wishlist y cualquier listado de productos.

    Variables esperadas:
      $product     (App\Models\Product) — requerido
      $franchise   (App\Models\Franchise|null) — opcional.
                   Si se omite, intenta usar $product->franchise.
                   Útil cuando el producto viene eager-loaded con la
                   franquicia padre (p.ej. en los carruseles de la portada).

    Variables globales (inyectadas por ViewServiceProvider):
      $wishlistIds  (array) — IDs de productos en wishlist del usuario.
                   Es un array vacío [] para usuarios no autenticados.
    ════════════════════════════════════════════════════════════════
--}}

@php
    // Resolver la franquicia: parámetro explícito o relación cargada del producto
    $fran       = $franchise ?? ($product->relationLoaded('franchise') ? $product->franchise : null);
    $franColor  = $fran->color ?? '#e9ecef';
    $enDeseos   = in_array($product->id, $wishlistIds ?? []);
@endphp

<div class="pc-card">

    {{-- ── Área de imagen con overlay hover ── --}}
    <div class="pc-img-area">

        {{-- Enlace a la ficha del producto (imagen) --}}
        <a href="{{ route('shop.product', $product->slug) }}"
           class="pc-img-link" aria-label="{{ $product->name }}">
            @if($product->image_url)
                <img src="{{ asset($product->image_url) }}"
                     alt="{{ $product->name }}"
                     class="pc-img"
                     loading="lazy">
            @else
                {{-- Placeholder con el color de la franquicia como fondo tenue --}}
                <div class="pc-img-placeholder"
                     style="background:{{ $franColor }}22">
                    <i class="bi bi-box-seam pc-img-icon"
                       style="color:{{ $franColor }}"></i>
                </div>
            @endif
        </a>

        {{-- Badge OFERTA / PRECOMPRA — esquina superior izquierda --}}
        @if($product->badgeLabel())
            <span class="pc-badge pc-badge--{{ $product->badgeColor() }}">
                {{ $product->badgeLabel() }}
            </span>
        @endif

        {{-- Overlay con botones de acción (visible al hacer hover) --}}
        <div class="pc-overlay" aria-hidden="true">

            {{-- Botón Añadir al carrito (AJAX via app.js) --}}
            <button class="pc-overlay-btn pc-overlay-btn--cart"
                    data-pc-cart="{{ $product->id }}"
                    title="Añadir al carrito">
                <i class="bi bi-cart-plus"></i>
                <span>Añadir</span>
            </button>

            {{-- Botón Lista de Deseos (AJAX via app.js)
                 Corazón relleno en rojo si el producto ya está en favoritos. --}}
            <button class="pc-overlay-btn pc-overlay-btn--fav {{ $enDeseos ? 'pc-overlay-btn--fav-activo' : '' }}"
                    data-pc-wishlist="{{ $product->id }}"
                    data-pc-wishlisted="{{ $enDeseos ? '1' : '0' }}"
                    title="{{ $enDeseos ? 'Quitar de favoritos' : 'Añadir a favoritos' }}">
                <i class="{{ $enDeseos ? 'bi bi-heart-fill' : 'bi bi-heart' }}"></i>
            </button>

        </div>

    </div>{{-- /.pc-img-area --}}

    {{-- ── Cuerpo de la tarjeta ── --}}
    <div class="pc-body">

        {{-- Nombre de categoría o franquicia en gris pequeño --}}
        <div class="pc-meta">
            {{ $product->category->name ?? $fran?->name ?? '—' }}
        </div>

        {{-- Nombre del producto — máximo 2 líneas --}}
        <a href="{{ route('shop.product', $product->slug) }}"
           class="pc-nombre text-decoration-none">
            {{ $product->name }}
        </a>

        {{-- Bloque de precios --}}
        <div class="pc-precios">

            @if($product->hasDiscount())
                {{-- Precio rebajado en verde --}}
                <span class="pc-precio-actual">
                    {{ number_format($product->price, 2, ',', '.') }} €
                </span>
                {{-- Precio original tachado --}}
                <span class="pc-precio-original">
                    {{ number_format($product->original_price, 2, ',', '.') }} €
                </span>
                {{-- Porcentaje de ahorro --}}
                <span class="pc-ahorro">-{{ $product->discountPercentage() }}%</span>
            @else
                <span class="pc-precio-actual">
                    {{ number_format($product->price, 2, ',', '.') }} €
                </span>
            @endif

            {{-- Etiqueta de estado especial --}}
            @if($product->status === 'preorder')
                <div class="pc-precompra-label">Precompra</div>
            @elseif($product->stock === 0)
                <div class="pc-sinstock-label">Sin stock</div>
            @endif

        </div>

    </div>{{-- /.pc-body --}}

</div>{{-- /.pc-card --}}
