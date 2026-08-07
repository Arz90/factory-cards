<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Factory Cards') | Tu tienda TCG</title>
    <meta name="description" content="@yield('meta_description', 'Tienda online de cartas Pokémon, Magic: The Gathering y juegos de mesa TCG. Mejores precios y envío rápido.')">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts: Nunito --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Estilos propios --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

{{-- ================================================================
     BLOQUE PEGAJOSO: Cabecera blanca + Navbar verde (sticky-top)
     Replica la estructura de dos barras de miceliongames.com
================================================================ --}}
<div id="bloque-cabecera-sticky" class="sticky-top">

    {{-- ── CABECERA PRINCIPAL (Fondo blanco) ──────────────────────────────────
         Estructura: Logo | Buscador + EVENTOS | Iconos de cuenta
    ─────────────────────────────────────────────────────────────────────────── --}}
    <header id="cabecera-principal" class="bg-white border-bottom">
        <div class="container-xl py-2">
            <div class="row align-items-center g-2">

                {{-- ── Columna izquierda: Logo de la tienda ── --}}
                <div class="col-auto col-lg-2">
                    <a href="{{ route('home') }}" class="text-decoration-none logo-tienda d-inline-block">
                        {{-- Placeholder de logo — reemplazar por <img> cuando esté el archivo --}}
                        <span class="logo-factory">Factory</span><span class="logo-cards">Cards</span>
                    </a>
                </div>

                {{-- ── Columna central: Buscador con categorías + botón EVENTOS ──
                     Oculto en mobile (el buscador aparece en el drawer offcanvas)
                ── --}}
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="d-flex align-items-center gap-2">

                        {{-- Formulario de búsqueda --}}
                        <form action="{{ route('shop.search') }}" method="GET"
                              class="d-flex flex-grow-1 buscador-principal" role="search">

                            {{-- Dropdown de categorías --}}
                            <select name="category"
                                    class="form-select form-select-sm buscador-select border-end-0 rounded-0 rounded-start">
                                <option value="">Categorías</option>
                                @foreach($headerCategories ?? [] as $cat)
                                    <option value="{{ $cat->slug }}"
                                            {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Campo de texto --}}
                            <input
                                type="search"
                                name="q"
                                class="form-control form-control-sm rounded-0 border-start-0 border-end-0"
                                placeholder="Buscar..."
                                value="{{ request('q') }}"
                                autocomplete="off"
                            >

                            {{-- Botón lupa --}}
                            <button class="btn btn-dark btn-sm rounded-0 rounded-end px-3 buscador-btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        {{-- Botón EVENTOS — enlaza a la página de eventos y torneos --}}
                        <a href="{{ route('events.index') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold text-nowrap btn-eventos">
                            EVENTOS
                        </a>

                    </div>
                </div>

                {{-- ── Columna derecha: Iconos de cuenta + Lista de deseos ── --}}
                <div class="col ms-auto col-lg-3">
                    <div class="d-flex justify-content-end align-items-center gap-3">

                        @auth
                            {{-- Usuario autenticado: icono de cuenta --}}
                            <a href="{{ route('user.dashboard') }}"
                               class="icono-cabecera text-decoration-none"
                               title="{{ auth()->user()->name }}">
                                <i class="bi bi-person fs-4 d-block text-center lh-1"></i>
                                <span class="icono-etiqueta">Mi cuenta</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                                {{-- Acceso rápido al panel admin para admins --}}
                                <a href="{{ route('admin.dashboard') }}"
                                   class="icono-cabecera text-decoration-none icono-admin"
                                   title="Panel Admin">
                                    <i class="bi bi-speedometer2 fs-4 d-block text-center lh-1"></i>
                                    <span class="icono-etiqueta">Admin</span>
                                </a>
                            @endif
                        @else
                            {{-- Invitado: botón de "Entrar" --}}
                            <a href="{{ route('login') }}"
                               class="icono-cabecera text-decoration-none">
                                <i class="bi bi-person fs-4 d-block text-center lh-1"></i>
                                <span class="icono-etiqueta">Entrar</span>
                            </a>
                        @endauth

                        {{-- Lista de deseos (stub — Fase 4) --}}
                        <a href="#" class="icono-cabecera text-decoration-none" title="Lista de deseos">
                            <i class="bi bi-heart fs-4 d-block text-center lh-1"></i>
                            <span class="icono-etiqueta">Deseos</span>
                        </a>

                        {{-- Carrito visible en mobile dentro de la cabecera --}}
                        <a href="{{ route('cart.index') }}"
                           class="icono-cabecera text-decoration-none position-relative d-lg-none">
                            <i class="bi bi-cart3 fs-4 d-block text-center lh-1"></i>
                            <span class="icono-etiqueta">Carrito</span>
                            @if(($cartCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>

                        {{-- Botón hamburguesa: abre menú móvil --}}
                        <button class="btn btn-outline-secondary d-lg-none border-0"
                                type="button"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#menuMovil"
                                aria-controls="menuMovil">
                            <i class="bi bi-list fs-4"></i>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- ── NAVBAR VERDE — Links de categorías + Carrito (desktop) ────────────
         Fondo verde brillante, texto blanco en mayúsculas.
         El carrito aparece como botón outline a la derecha.
    ─────────────────────────────────────────────────────────────────────────── --}}
    <nav id="navbar-verde" class="d-none d-lg-block">
        <div class="container-xl">
            <div class="d-flex align-items-stretch">

                {{-- ── Links de navegación principal ── --}}
                <ul class="nav navbar-verde-nav me-auto">

                    {{-- JUEGOS TCG: dropdown con franquicias --}}
                    <li class="nav-item dropdown">
                        <a class="nav-verde-link dropdown-toggle" href="{{ route('shop.catalog') }}"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            JUEGOS TCG
                        </a>
                        <ul class="dropdown-menu dropdown-verde">
                            @foreach($headerFranchises ?? [] as $f)
                                <li>
                                    <a class="dropdown-item" href="{{ route('shop.franchise', $f->slug) }}">
                                        {{ $f->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    {{-- JUEGOS DE MESA --}}
                    <li class="nav-item">
                        <a class="nav-verde-link" href="{{ route('shop.catalog') }}">JUEGOS DE MESA</a>
                    </li>

                    {{-- WARHAMMER --}}
                    <li class="nav-item">
                        <a class="nav-verde-link" href="{{ route('shop.franchise', 'warhammer') }}">WARHAMMER</a>
                    </li>

                    {{-- JUEGOS DE ROL --}}
                    <li class="nav-item">
                        <a class="nav-verde-link" href="{{ route('shop.catalog') }}">JUEGOS DE ROL</a>
                    </li>

                    {{-- ACCESORIOS --}}
                    <li class="nav-item">
                        <a class="nav-verde-link" href="{{ route('shop.category', 'accesorios') }}">ACCESORIOS</a>
                    </li>

                    {{-- VENDE TUS CARTAS (stub) --}}
                    <li class="nav-item">
                        <a class="nav-verde-link nav-verde-link--resaltado" href="#">VENDE TUS CARTAS</a>
                    </li>

                </ul>

                {{-- ── Botón carrito (outline verde oscuro) ── --}}
                <div class="d-flex align-items-center py-1">
                    <a href="{{ route('cart.index') }}" class="btn-carrito-navbar">
                        <i class="bi bi-cart3 me-1"></i>
                        CARRITO
                        <span class="carrito-precio ms-1">{{ number_format($cartTotal ?? 0, 2, ',', '.') }} €</span>
                        @if(($cartCount ?? 0) > 0)
                            <span class="badge bg-white text-success rounded-pill ms-1 small">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>

            </div>
        </div>
    </nav>

</div>{{-- fin bloque-cabecera-sticky --}}


{{-- ================================================================
     BARRA DE FRANQUICIAS — Iconos monocromáticos con etiqueta
     Fondo gris muy claro, scroll horizontal sin scrollbar visible.
     No es pegajosa: se oculta al hacer scroll.
================================================================ --}}
<div id="barra-franquicias" class="border-bottom bg-white">
    <div class="container-xl">
        <div class="d-flex align-items-center gap-0 franquicias-scroll py-1">

            {{-- Todo --}}
            <a href="{{ route('shop.catalog') }}"
               class="franquicia-chip flex-shrink-0 text-center text-decoration-none
                       {{ request()->routeIs('shop.catalog') && !request()->route('slug') ? 'franquicia-chip--activo' : '' }}">
                <span class="franquicia-mono-icono"><i class="bi bi-grid"></i></span>
                <span class="franquicia-mono-etiqueta">TODO</span>
            </a>

            {{-- Divisor visual --}}
            <div class="flex-shrink-0 mx-1" style="width:1px;height:32px;background:#ddd;"></div>

            {{-- Una chip por franquicia activa --}}
            @foreach($headerFranchises ?? [] as $franchise)
                <a href="{{ route('shop.franchise', $franchise->slug) }}"
                   class="franquicia-chip flex-shrink-0 text-center text-decoration-none
                           {{ request()->route('slug') === $franchise->slug ? 'franquicia-chip--activo' : '' }}"
                   title="{{ $franchise->name }}">
                    @if($franchise->icon_url)
                        {{-- Imagen monocromatica via CSS filter --}}
                        <img src="{{ asset($franchise->icon_url) }}"
                             alt="{{ $franchise->name }}"
                             class="franquicia-mono-img">
                    @else
                        <span class="franquicia-mono-icono"><i class="bi bi-collection"></i></span>
                    @endif
                    <span class="franquicia-mono-etiqueta">{{ Str::upper($franchise->name) }}</span>
                </a>
            @endforeach

        </div>
    </div>
</div>


{{-- ================================================================
     MENÚ MÓVIL OFFCANVAS
================================================================ --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="menuMovil" aria-labelledby="menuMovilLabel">

    <div class="offcanvas-header" style="background:var(--fc-verde);">
        <a href="{{ route('home') }}"
           class="text-white text-decoration-none fw-bold fs-5"
           id="menuMovilLabel">
            <span style="color:#FFE066;">Factory</span> Cards
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body p-0">

        {{-- Buscador mobile --}}
        <div class="p-3 bg-light border-bottom">
            <form action="{{ route('shop.search') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="q" class="form-control"
                       placeholder="Buscar productos..." value="{{ request('q') }}">
                <button class="btn btn-dark px-3" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <ul class="list-group list-group-flush">

            {{-- Inicio --}}
            <li class="list-group-item">
                <a href="{{ route('home') }}"
                   class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                    <i class="bi bi-house-fill" style="color:var(--fc-verde)"></i> Inicio
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('shop.catalog') }}"
                   class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                    <i class="bi bi-shop" style="color:var(--fc-verde)"></i> Ver toda la tienda
                </a>
            </li>

            {{-- Franquicias --}}
            <li class="list-group-item bg-light py-2">
                <span class="small fw-bold text-muted text-uppercase">Franquicias</span>
            </li>
            @foreach($headerFranchises ?? [] as $franchise)
                <li class="list-group-item">
                    <a href="{{ route('shop.franchise', $franchise->slug) }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        @if($franchise->icon_url)
                            <img src="{{ asset($franchise->icon_url) }}" alt=""
                                 style="width:20px;height:20px;object-fit:contain;">
                        @else
                            <i class="bi bi-collection-fill" style="color:var(--fc-verde)"></i>
                        @endif
                        {{ $franchise->name }}
                    </a>
                </li>
            @endforeach

            {{-- Categorías --}}
            <li class="list-group-item bg-light py-2">
                <span class="small fw-bold text-muted text-uppercase">Categorías</span>
            </li>
            @foreach($headerCategories ?? [] as $cat)
                <li class="list-group-item">
                    <a href="{{ route('shop.category', $cat->slug) }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-tag-fill" style="color:var(--fc-verde)"></i> {{ $cat->name }}
                    </a>
                </li>
            @endforeach

            {{-- Cuenta --}}
            <li class="list-group-item bg-light py-2">
                <span class="small fw-bold text-muted text-uppercase">Mi cuenta</span>
            </li>
            @auth
                <li class="list-group-item">
                    <a href="{{ route('user.dashboard') }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person-circle" style="color:var(--fc-verde)"></i>
                        {{ auth()->user()->name }}
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('user.orders') }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-bag-check-fill" style="color:var(--fc-verde)"></i> Mis pedidos
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                    <li class="list-group-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="d-flex align-items-center gap-2 text-decoration-none fw-semibold py-1"
                           style="color:var(--fc-verde)">
                            <i class="bi bi-speedometer2"></i> Panel Admin
                        </a>
                    </li>
                @endif
                <li class="list-group-item">
                    <form action="{{ route('logout', absolute: false) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="btn btn-link p-0 d-flex align-items-center gap-2 text-danger text-decoration-none">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </button>
                    </form>
                </li>
            @else
                <li class="list-group-item">
                    <a href="{{ route('login') }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person" style="color:var(--fc-verde)"></i> Iniciar sesión
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('register') }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person-plus" style="color:var(--fc-verde)"></i> Registrarse
                    </a>
                </li>
            @endauth

        </ul>
    </div>
</div>


{{-- ================================================================
     CONTENIDO PRINCIPAL — Las vistas inyectan aquí con @yield
================================================================ --}}
<main id="contenido-principal">

    {{-- Alertas de sesión (flash messages) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0 rounded-0 border-0 text-center small" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 border-0 text-center small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>


{{-- ================================================================
     FOOTER — SEO optimizado y centrado en la confianza del usuario.
     Estructura: footer principal (4 columnas) + sub-footer de pagos.
     Rutas inexistentes usan href="#" para evitar RouteNotFoundException.
================================================================ --}}
<footer class="footer-principal" itemscope itemtype="https://schema.org/WPFooter">

    {{-- ── SECCIÓN SUPERIOR: 4 columnas de contenido ── --}}
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">

                {{-- Columna 1: Marca + descripción + Newsletter + Redes --}}
                <div class="col-12 col-lg-4">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="text-decoration-none d-inline-block mb-3" aria-label="Factory Cards — Inicio">
                        <span class="footer-logo-factory">Factory</span><span class="footer-logo-cards">Cards</span>
                    </a>

                    {{-- Descripción (texto SEO relevante) --}}
                    <p class="footer-desc mb-4">
                        Tu tienda especializada en TCG: Pokémon, Magic: The Gathering, One Piece y mucho más.
                        Stock actualizado, precios competitivos y envío rápido a toda España.
                    </p>

                    {{-- Newsletter inline --}}
                    <p class="footer-col-title mb-2" style="font-size:.72rem;">OFERTAS EXCLUSIVAS PARA SUSCRIPTORES</p>
                    <form action="#" method="POST" class="d-flex gap-2 mb-4" aria-label="Formulario de suscripción al boletín">
                        @csrf
                        <input type="email"
                               class="form-control form-control-sm footer-newsletter-input"
                               placeholder="tu@email.com"
                               aria-label="Tu correo electrónico"
                               required>
                        <button type="submit" class="btn btn-sm footer-newsletter-btn text-nowrap">
                            Suscribirme
                        </button>
                    </form>

                    {{-- Redes sociales --}}
                    <div class="d-flex gap-2" aria-label="Redes sociales de Factory Cards">
                        <a href="#" class="footer-social-btn" title="Instagram" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="footer-social-btn" title="TikTok" aria-label="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="#" class="footer-social-btn" title="YouTube" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="footer-social-btn" title="Discord" aria-label="Discord">
                            <i class="bi bi-discord"></i>
                        </a>
                    </div>
                </div>

                {{-- Columna 2: Franquicias — enlaces de valor SEO --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <h3 class="footer-col-title">Franquicias</h3>
                    <ul class="footer-links" aria-label="Franquicias disponibles">
                        {{-- Franquicias dinámicas desde la BD (cacheadas 5 min en ViewServiceProvider) --}}
                        @foreach($headerFranchises ?? [] as $franchise)
                            <li>
                                <a href="{{ route('shop.franchise', $franchise->slug) }}">
                                    {{ $franchise->name }}
                                </a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('shop.catalog') }}">Juegos de Mesa</a></li>
                        <li><a href="{{ route('shop.catalog') }}">Ver todo el catálogo</a></li>
                    </ul>
                </div>

                {{-- Columna 3: Atención al Cliente --}}
                <div class="col-6 col-md-4 col-lg-3">
                    <h3 class="footer-col-title">Atención al Cliente</h3>
                    <ul class="footer-links" aria-label="Atención al cliente">
                        {{-- href="#" porque estas páginas aún no existen en web.php --}}
                        <li><a href="#">Preguntas frecuentes (FAQ)</a></li>
                        <li><a href="#">Envíos y entregas</a></li>
                        <li><a href="#">Devoluciones y reembolsos</a></li>
                        <li><a href="#">Seguimiento de pedido</a></li>
                        <li><a href="#">Contacto</a></li>
                        {{-- Enlace condicional: pedidos si está autenticado, login si no --}}
                        @auth
                            <li><a href="{{ route('user.orders') }}">Mis pedidos</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Columna 4: Información Legal --}}
                <div class="col-6 col-md-4 col-lg-3">
                    <h3 class="footer-col-title">Información Legal</h3>
                    <ul class="footer-links" aria-label="Información legal">
                        {{-- href="#" porque las páginas legales aún no están en web.php --}}
                        <li><a href="#">Aviso Legal</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Política de Cookies</a></li>
                        <li><a href="#">Sobre nosotros</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- ── SECCIÓN INFERIOR: copyright + métodos de pago ── --}}
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center g-3">

                {{-- Copyright --}}
                <div class="col-12 col-md-5">
                    <p class="footer-copyright mb-0">
                        &copy; {{ date('Y') }} Factory Cards. Todos los derechos reservados.
                    </p>
                </div>

                {{-- Métodos de pago + SSL --}}
                <div class="col-12 col-md-7">
                    <div class="d-flex align-items-center justify-content-md-end flex-wrap gap-2">
                        <span class="footer-pay-badge"><i class="bi bi-credit-card-fill me-1"></i>Visa</span>
                        <span class="footer-pay-badge"><i class="bi bi-credit-card me-1"></i>Mastercard</span>
                        <span class="footer-pay-badge"><i class="bi bi-paypal me-1"></i>PayPal</span>
                        <span class="footer-pay-badge">Bizum</span>
                        <span class="footer-pay-badge"><i class="bi bi-lightning-charge-fill me-1"></i>Stripe</span>
                        <span class="footer-ssl">
                            <i class="bi bi-shield-lock-fill me-1"></i>Pago Seguro SSL 256-bit
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</footer>


{{-- ================================================================
     CHATBOT FLOTANTE — Asistente virtual nativo de Factory Cards
================================================================ --}}
@include('layouts.partials.chatbot')

{{-- Bootstrap JS Bundle (incluye Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- JS propio --}}
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>
