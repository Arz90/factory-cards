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

                        {{-- Botón EVENTOS — negro redondeado --}}
                        <a href="#" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold text-nowrap btn-eventos">
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
     FOOTER
================================================================ --}}
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">

            {{-- Col 1: Marca --}}
            <div class="col-12 col-md-4">
                <h5 class="fw-black mb-3">
                    <span class="text-warning">Factory</span> Cards
                </h5>
                <p class="text-muted small">
                    Tu tienda de referencia para cartas Pokémon, Magic: The Gathering y juegos de mesa TCG.
                    Productos originales, stock actualizado y envío rápido.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-muted fs-5 footer-social" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-muted fs-5 footer-social" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-muted fs-5 footer-social" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-muted fs-5 footer-social" title="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Col 2: Franquicias --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold mb-3 small" style="color:var(--fc-verde)">Franquicias</h6>
                <ul class="list-unstyled small">
                    @foreach($headerFranchises ?? [] as $franchise)
                        <li class="mb-1">
                            <a href="{{ route('shop.franchise', $franchise->slug) }}"
                               class="text-muted text-decoration-none footer-link">
                                {{ $franchise->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3: Información --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold mb-3 small" style="color:var(--fc-verde)">Información</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none footer-link">Sobre nosotros</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none footer-link">Cómo comprar</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none footer-link">Envíos y entregas</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none footer-link">Devoluciones</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none footer-link">Contacto</a></li>
                </ul>
            </div>

            {{-- Col 4: Mi cuenta --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold mb-3 small" style="color:var(--fc-verde)">Mi cuenta</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><a href="{{ route('login') }}" class="text-muted text-decoration-none footer-link">Iniciar sesión</a></li>
                    <li class="mb-1"><a href="{{ route('register') }}" class="text-muted text-decoration-none footer-link">Registrarse</a></li>
                    <li class="mb-1"><a href="{{ route('user.orders') }}" class="text-muted text-decoration-none footer-link">Mis pedidos</a></li>
                    <li class="mb-1"><a href="{{ route('cart.index') }}" class="text-muted text-decoration-none footer-link">Carrito</a></li>
                </ul>
            </div>

            {{-- Col 5: Pago seguro --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold mb-3 small" style="color:var(--fc-verde)">Pago seguro</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-secondary p-2"><i class="bi bi-credit-card-2-front-fill me-1"></i>Tarjeta</span>
                    <span class="badge bg-secondary p-2"><i class="bi bi-bank me-1"></i>TPV Virtual</span>
                    <span class="badge bg-secondary p-2"><i class="bi bi-shield-check me-1"></i>3D Secure</span>
                </div>
                <div class="mt-3">
                    <i class="bi bi-lock-fill text-success me-1 small"></i>
                    <span class="text-muted small">Conexión SSL cifrada</span>
                </div>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center">
            <div class="col-md-6 small text-muted">
                &copy; {{ date('Y') }} Factory Cards. Todos los derechos reservados.
            </div>
            <div class="col-md-6 text-md-end small text-muted">
                <a href="#" class="text-muted text-decoration-none me-3">Política de privacidad</a>
                <a href="#" class="text-muted text-decoration-none me-3">Aviso legal</a>
                <a href="#" class="text-muted text-decoration-none">Cookies</a>
            </div>
        </div>
    </div>
</footer>


{{-- Bootstrap JS Bundle (incluye Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- JS propio --}}
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>
