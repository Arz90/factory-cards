{{--
    Vista: shop/home
    Propósito: Portada de la tienda pública.
    Secciones: Hero slider | Trust badges | Sección destacada | Productos featured
--}}
@extends('layouts.app')

@section('title', 'Factory Cards — Tienda TCG')
@section('meta_description', 'Tienda online especializada en TCG. Pokémon, Magic: The Gathering, One Piece y más. Envío rápido y precios competitivos.')

{{-- CSS de Swiper.js — solo se carga en la portada --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush

@section('content')

{{-- ================================================================
     HERO BANNER — Slider dinámico (banners gestionados desde admin)
     Los slides se cargan desde la tabla 'banners' vía ShopController.
     Si no hay banners activos se muestra un slide de bienvenida.
================================================================ --}}
@if($banners->isNotEmpty())

<div id="heroSlider"
     class="carousel slide carousel-fade"
     data-bs-ride="carousel"
     data-bs-interval="5000">

    {{-- ── Indicadores de posición (uno por banner) ── --}}
    <div class="carousel-indicators hero-indicadores">
        @foreach($banners as $indice => $banner)
            <button type="button"
                    data-bs-target="#heroSlider"
                    data-bs-slide-to="{{ $indice }}"
                    class="{{ $indice === 0 ? 'active' : '' }}"
                    aria-current="{{ $indice === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $indice + 1 }}">
            </button>
        @endforeach
    </div>

    {{-- ── Slides generados dinámicamente desde la BD ── --}}
    <div class="carousel-inner">
        @foreach($banners as $indice => $banner)
            <div class="carousel-item {{ $indice === 0 ? 'active' : '' }}">
                <div class="hero-slide">

                    {{-- Imagen de fondo del banner (o placeholder si no hay archivo) --}}
                    <img src="{{ $banner->urlImagen() }}"
                         class="hero-bg-img"
                         alt="{{ $banner->title }}">

                    {{-- Overlay degradado para que el texto sea legible --}}
                    <div class="hero-overlay"></div>

                    {{-- Texto superpuesto a la izquierda --}}
                    <div class="container-xl hero-contenido">
                        <div class="row">
                            <div class="col-lg-6 hero-texto">

                                {{-- Subtítulo como etiqueta amarilla (opcional) --}}
                                @if($banner->subtitle)
                                    <span class="hero-fecha">{{ $banner->subtitle }}</span>
                                @endif

                                {{-- Título principal del slide --}}
                                <h1 class="hero-titulo">{{ $banner->title }}</h1>

                                {{-- Botón de llamada a la acción --}}
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="btn-hero-precompra">
                                        {{ $banner->button_text }}
                                    </a>
                                @else
                                    <a href="{{ route('shop.catalog') }}" class="btn-hero-precompra">
                                        {{ $banner->button_text }}
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Controles de navegación laterales ── --}}
    <button class="carousel-control-prev hero-control" type="button"
            data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next hero-control" type="button"
            data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>

</div>{{-- /#heroSlider --}}

@else
    {{-- Estado vacío: no hay banners activos — visible solo para admin logueado --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="bg-light border text-center py-5">
            <i class="bi bi-image text-muted fs-1"></i>
            <p class="text-muted mt-2 mb-3">No hay banners activos. Crea uno desde el panel de administración.</p>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Crear banner
            </a>
        </div>
        @endif
    @endauth
@endif


{{-- ================================================================
     TRUST BADGES — 4 columnas con icono + título + subtítulo
     Fondo blanco, separadores entre columnas.
================================================================ --}}
<div class="trust-badges-barra border-bottom">
    <div class="container-xl">
        <div class="row g-0">

            {{-- Envío gratuito --}}
            <div class="col-6 col-md-3 trust-badge border-end">
                <i class="bi bi-truck trust-badge-icono"></i>
                <div>
                    <div class="trust-badge-titulo">Envío gratuito</div>
                    <div class="trust-badge-subtitulo">En pedidos superiores a 50€</div>
                </div>
            </div>

            {{-- Pago seguro --}}
            <div class="col-6 col-md-3 trust-badge border-end">
                <i class="bi bi-lock-fill trust-badge-icono"></i>
                <div>
                    <div class="trust-badge-titulo">100% Pago Seguro</div>
                    <div class="trust-badge-subtitulo">Stripe · TPV Virtual Redsys</div>
                </div>
            </div>

            {{-- Devoluciones --}}
            <div class="col-6 col-md-3 trust-badge border-end">
                <i class="bi bi-arrow-return-left trust-badge-icono"></i>
                <div>
                    <div class="trust-badge-titulo">14 Días de devolución</div>
                    <div class="trust-badge-subtitulo">Sin preguntas, sin complicaciones</div>
                </div>
            </div>

            {{-- Soporte --}}
            <div class="col-6 col-md-3 trust-badge">
                <i class="bi bi-headset trust-badge-icono"></i>
                <div>
                    <div class="trust-badge-titulo">24/7 Soporte online</div>
                    <div class="trust-badge-subtitulo">Respuesta en menos de 24h</div>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- ================================================================
     SECCIÓN DESTACADA — Split 50/50: Texto izquierda | Imagen derecha
     La imagen ocupa toda la mitad derecha a sangre (sin padding).
================================================================ --}}
<section class="seccion-destacada">
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-50">

            {{-- ── Mitad izquierda: texto e información del producto ── --}}
            <div class="col-lg-6 destacado-lado-texto">
                <div class="destacado-contenido">

                    {{-- Etiqueta pequeña de franquicia --}}
                    <span class="destacado-franquicia-label">
                        <i class="bi bi-collection-fill me-1"></i>MAGIC: THE GATHERING
                    </span>

                    {{-- Título grande del producto --}}
                    <h2 class="destacado-titulo">SECRETOS DE<br>STRIXHAVEN</h2>

                    {{-- Fecha de lanzamiento --}}
                    <p class="destacado-fecha">
                        <i class="bi bi-calendar3 me-2"></i>Lanzamiento: 15 de noviembre de 2025
                    </p>

                    {{-- Descripción breve --}}
                    <p class="destacado-descripcion">
                        La academia de magos más poderosa del multiverso abre sus puertas.
                        Consigue tu caja sellada antes del lanzamiento oficial al precio más competitivo del mercado.
                    </p>

                    {{-- Botón de precompra verde brillante --}}
                    <a href="{{ route('shop.catalog') }}" class="btn-destacado-precompra">
                        <i class="bi bi-bag-plus me-2"></i>PRECOMPRA AHORA
                    </a>

                </div>
            </div>

            {{-- ── Mitad derecha: imagen del producto a sangre ── --}}
            <div class="col-lg-6 destacado-lado-imagen">
                {{-- Placeholder hasta tener imagen real del producto --}}
                <img
                    src="https://placehold.co/800x560/1a2332/4a90d9?text=Secretos+de+Strixhaven"
                    alt="Secretos de Strixhaven — Magic: The Gathering"
                    class="destacado-imagen"
                >
            </div>

        </div>
    </div>
</section>


{{-- ================================================================
     CARRUSELES POR FRANQUICIA
     Una sección por cada franquicia activa con productos.
     Cada carrusel es un Swiper independiente con flechas.
================================================================ --}}
@if(isset($porFranquicia) && $porFranquicia->count())

<div class="secciones-franquicia">
@foreach($porFranquicia as $franquicia)
    @if($franquicia->products->isEmpty()) @continue @endif

    {{-- ── Sección de franquicia ── --}}
    <section class="seccion-franquicia py-4" id="franquicia-{{ $franquicia->slug }}">
        <div class="container-xl">

            {{-- Cabecera: título + línea decorativa + enlace "Ver todos" --}}
            <div class="franquicia-seccion-header d-flex align-items-center justify-content-between mb-3">
                <div class="franquicia-titulo-grupo">
                    {{-- Línea de color a la izquierda del título --}}
                    <span class="franquicia-titulo-barra"
                          style="background:{{ $franquicia->color ?? 'var(--fc-verde)' }}"></span>
                    <h2 class="franquicia-titulo-h2 mb-0">{{ $franquicia->name }}</h2>
                </div>
                <a href="{{ route('shop.franchise', $franquicia->slug) }}"
                   class="btn btn-outline-secondary btn-sm franquicia-ver-todos">
                    Ver todos <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            {{-- Swiper — cada franquicia tiene su propio contenedor --}}
            <div class="swiper swiper-franquicia" data-slug="{{ $franquicia->slug }}">

                {{-- Botón anterior (se posiciona con CSS fuera del wrapper) --}}
                <div class="swiper-button-prev swiper-btn-franquicia"></div>

                <div class="swiper-wrapper">
                    @foreach($franquicia->products as $producto)
                    <div class="swiper-slide">

                        {{-- ── TARJETA DE PRODUCTO ── --}}
                        <div class="pc-card">

                            {{-- Área de imagen con overlay hover --}}
                            <div class="pc-img-area">

                                {{-- Imagen o placeholder con color de franquicia --}}
                                <a href="{{ route('shop.product', $producto->slug) }}"
                                   class="pc-img-link" tabindex="-1" aria-hidden="true">
                                    @if($producto->image_url)
                                        <img src="{{ asset($producto->image_url) }}"
                                             alt="{{ $producto->name }}"
                                             class="pc-img"
                                             loading="lazy">
                                    @else
                                        <div class="pc-img-placeholder"
                                             style="background:{{ $franquicia->color ?? '#e9ecef' }}22">
                                            <i class="bi bi-box-seam pc-img-icon"
                                               style="color:{{ $franquicia->color ?? '#6c757d' }}"></i>
                                        </div>
                                    @endif
                                </a>

                                {{-- Badge OFERTA / PRECOMPRA (esquina superior izquierda) --}}
                                @if($producto->badgeLabel())
                                    <span class="pc-badge pc-badge--{{ $producto->badgeColor() }}">
                                        {{ $producto->badgeLabel() }}
                                    </span>
                                @endif

                                {{-- Overlay con botones: aparece al hacer hover sobre la imagen --}}
                                <div class="pc-overlay" aria-hidden="true">

                                    {{-- Botón Añadir al Carrito (AJAX) --}}
                                    <button class="pc-overlay-btn pc-overlay-btn--cart"
                                            data-producto-id="{{ $producto->id }}"
                                            data-url="{{ route('cart.add', $producto->id) }}"
                                            title="Añadir al carrito">
                                        <i class="bi bi-cart-plus"></i>
                                        <span>Añadir</span>
                                    </button>

                                    {{-- Botón Favoritos (stub — Fase 4) --}}
                                    <button class="pc-overlay-btn pc-overlay-btn--fav"
                                            title="Añadir a favoritos">
                                        <i class="bi bi-heart"></i>
                                    </button>

                                </div>

                            </div>{{-- /.pc-img-area --}}

                            {{-- Cuerpo de la tarjeta --}}
                            <div class="pc-body">

                                {{-- Franquicia / Categoría en gris pequeño --}}
                                <div class="pc-meta">
                                    {{ $producto->category->name ?? $franquicia->name }}
                                </div>

                                {{-- Nombre del producto (enlace, máximo 2 líneas) --}}
                                <a href="{{ route('shop.product', $producto->slug) }}"
                                   class="pc-nombre text-decoration-none">
                                    {{ $producto->name }}
                                </a>

                                {{-- Precios --}}
                                <div class="pc-precios">
                                    @if($producto->hasDiscount())
                                        {{-- Precio rebajado + precio tachado + % ahorro --}}
                                        <span class="pc-precio-actual">
                                            {{ number_format($producto->price, 2, ',', '.') }} €
                                        </span>
                                        <span class="pc-precio-original">
                                            {{ number_format($producto->original_price, 2, ',', '.') }} €
                                        </span>
                                        <span class="pc-ahorro">
                                            -{{ $producto->discountPercentage() }}%
                                        </span>
                                    @else
                                        <span class="pc-precio-actual">
                                            {{ number_format($producto->price, 2, ',', '.') }} €
                                        </span>
                                    @endif

                                    {{-- Estado: sin stock / precompra --}}
                                    @if($producto->status === 'preorder')
                                        <div class="pc-precompra-label">Precompra</div>
                                    @elseif($producto->stock === 0)
                                        <div class="pc-sinstock-label">Sin stock</div>
                                    @endif
                                </div>

                            </div>{{-- /.pc-body --}}

                        </div>{{-- /.pc-card --}}

                    </div>{{-- /.swiper-slide --}}
                    @endforeach
                </div>{{-- /.swiper-wrapper --}}

                {{-- Botón siguiente --}}
                <div class="swiper-button-next swiper-btn-franquicia"></div>

            </div>{{-- /.swiper --}}

        </div>
    </section>

    {{-- Separador sutil entre secciones (excepto la última) --}}
    @if(!$loop->last)
        <div class="franquicia-separador"></div>
    @endif

@endforeach
</div>{{-- /.secciones-franquicia --}}

@endif

@endsection

{{-- JS de Swiper + inicialización — al final del body vía stack --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Configuración responsive compartida por todos los carruseles ─────
    const breakpoints = {
        0: {
            // Móvil: 1.5 tarjetas para dar pista visual de que hay más
            slidesPerView: 1.5,
            spaceBetween: 10,
        },
        576: {
            // Mobile landscape / tablet pequeña
            slidesPerView: 2.5,
            spaceBetween: 12,
        },
        768: {
            // Tablet
            slidesPerView: 3,
            spaceBetween: 16,
        },
        992: {
            // Desktop
            slidesPerView: 4,
            spaceBetween: 16,
        },
        1200: {
            // Desktop grande
            slidesPerView: 5,
            spaceBetween: 20,
        },
    };

    // ── Inicializar un Swiper independiente por cada sección de franquicia ─
    document.querySelectorAll('.swiper-franquicia').forEach(function (contenedor) {
        new Swiper(contenedor, {
            breakpoints:      breakpoints,
            grabCursor:       true,
            // Flechas de navegación
            navigation: {
                nextEl: contenedor.querySelector('.swiper-button-next'),
                prevEl: contenedor.querySelector('.swiper-button-prev'),
            },
            // Accesibilidad
            a11y: {
                prevSlideMessage: 'Producto anterior',
                nextSlideMessage: 'Producto siguiente',
            },
        });
    });

    // ── Botón "Añadir al carrito" en el overlay (AJAX) ──────────────────
    const TOKEN_CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    document.addEventListener('click', function (e) {
        const boton = e.target.closest('.pc-overlay-btn--cart');
        if (!boton) return;

        // Evitar doble clic mientras procesa
        if (boton.dataset.cargando === 'true') return;
        boton.dataset.cargando = 'true';

        const url  = boton.dataset.url;
        const icon = boton.querySelector('i');
        const span = boton.querySelector('span');

        // Feedback visual inmediato
        icon.className  = 'bi bi-hourglass-split';
        span.textContent = '…';

        fetch(url, {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': TOKEN_CSRF,
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ quantity: 1 }),
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Error ' + res.status);
            return res.json();
        })
        .then(function () {
            // Éxito: icono de check durante 1.5 s
            icon.className   = 'bi bi-check-lg';
            span.textContent = '¡Añadido!';
            boton.classList.add('pc-overlay-btn--ok');

            // Actualizar el contador del carrito en el header si existe
            const contadores = document.querySelectorAll('.cart-badge, .carrito-precio');
            // El contador exacto requeriría otro fetch; recargamos solo si hay contador visible
            if (contadores.length) {
                // Refrescar página para actualizar navbar de forma simple y robusta
                // (alternativa a mantener un estado global de carrito en JS)
                setTimeout(function () { location.reload(); }, 1000);
            }
        })
        .catch(function (err) {
            console.error('[Carrusel] Error al añadir al carrito:', err);
            icon.className   = 'bi bi-exclamation-triangle';
            span.textContent = 'Error';
        })
        .finally(function () {
            // Restaurar el estado del botón tras 2 segundos
            setTimeout(function () {
                icon.className          = 'bi bi-cart-plus';
                span.textContent        = 'Añadir';
                boton.dataset.cargando  = 'false';
                boton.classList.remove('pc-overlay-btn--ok');
            }, 2000);
        });
    });

})();
</script>
@endpush
