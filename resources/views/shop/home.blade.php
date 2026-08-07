{{--
    Vista: shop/home
    Propósito: Portada de la tienda pública.
    Secciones: Hero slider | Trust badges | Sección destacada | Productos featured
--}}
@extends('layouts.app')

@section('title', 'Factory Cards — Tienda TCG')
@section('meta_description', 'Tienda online especializada en TCG. Pokémon, Magic: The Gathering, One Piece y más. Envío rápido y precios competitivos.')

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
     PRODUCTOS DESTACADOS — Grid de tarjetas (si hay featured)
================================================================ --}}
@if(isset($featured) && $featured->count())
<section class="py-5">
    <div class="container-xl">

        {{-- Título de sección --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="h4 fw-black mb-0">
                <span class="seccion-titulo-acento"></span>
                Productos destacados
            </h2>
            <a href="{{ route('shop.catalog') }}"
               class="btn btn-outline-secondary btn-sm">
                Ver todos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        {{-- Grid de productos --}}
        <div class="row g-3">
            @foreach($featured as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none">
                    <div class="product-card card h-100 position-relative">

                        {{-- Badge de estado (oferta / precompra) --}}
                        @if($product->badgeLabel())
                            <span class="product-badge badge bg-{{ $product->badgeColor() }}">
                                {{ $product->badgeLabel() }}
                            </span>
                        @endif

                        {{-- Imagen del producto --}}
                        @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}"
                                 class="card-img-top"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            {{-- Nombre del producto (truncado a 2 líneas) --}}
                            <div class="card-title">{{ $product->name }}</div>

                            {{-- Precio --}}
                            <div class="mt-auto pt-2">
                                <span class="price-current">
                                    {{ number_format($product->price, 2, ',', '.') }} €
                                </span>
                                @if($product->hasDiscount())
                                    <span class="price-original ms-1">
                                        {{ number_format($product->original_price, 2, ',', '.') }} €
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

@endsection
