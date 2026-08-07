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
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Estilos propios --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

{{-- ============================================================
     HEADER (3 NIVELES)
============================================================ --}}
<header id="site-header">

    {{-- ─── NIVEL 1: TOPBAR (Buscador + Selector de categorías) ─────────── --}}
    <div class="topbar bg-dark text-white py-2 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center g-2">

                {{-- Logo pequeño / Slogan --}}
                <div class="col-lg-3">
                    <a href="{{ route('home') }}" class="text-white text-decoration-none fw-bold fs-5 topbar-brand">
                        <span class="text-warning">Factory</span> Cards
                    </a>
                </div>

                {{-- Buscador con selector de categorías --}}
                <div class="col-lg-6">
                    <form action="{{ route('shop.search') }}" method="GET" class="d-flex" role="search">
                        <select name="category" class="form-select form-select-sm topbar-category-select rounded-0 rounded-start border-0" style="width:160px; flex-shrink:0;">
                            <option value="">Todas las categorías</option>
                            @foreach($headerCategories ?? [] as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <input
                            type="search"
                            name="q"
                            class="form-control form-control-sm rounded-0 border-0"
                            placeholder="Buscar cartas, expansiones, juegos..."
                            value="{{ request('q') }}"
                            autocomplete="off"
                        >
                        <button class="btn btn-warning btn-sm rounded-0 rounded-end px-3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                {{-- Links de cuenta --}}
                <div class="col-lg-3 text-end">
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="text-white text-decoration-none small me-3">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-warning text-decoration-none small me-3">
                                <i class="bi bi-speedometer2 me-1"></i>Admin
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-white text-decoration-none p-0 small">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white text-decoration-none small me-3">
                            <i class="bi bi-person me-1"></i>Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="text-white text-decoration-none small">
                            <i class="bi bi-person-plus me-1"></i>Registro
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    {{-- ─── NIVEL 2: MENÚ PRINCIPAL + CARRITO ────────────────────────────── --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" id="main-navbar">
        <div class="container">

            {{-- Logo principal (visible en mobile y desktop) --}}
            <a class="navbar-brand fw-black fs-4" href="{{ route('home') }}">
                <span class="text-warning">Factory</span><span class="text-white"> Cards</span>
            </a>

            {{-- Botones mobile: carrito + hamburguesa --}}
            <div class="d-flex d-lg-none align-items-center gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-warning btn-sm position-relative">
                    <i class="bi bi-cart3 fs-5"></i>
                    @if(($cartCount ?? 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- Navegación desktop --}}
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('shop.*') ? 'active' : '' }}"
                           href="{{ route('shop.catalog') }}" role="button" data-bs-toggle="dropdown">
                            Tienda
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('shop.catalog') }}">Ver todo</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($headerCategories ?? [] as $cat)
                                <li>
                                    <a class="dropdown-item" href="{{ route('shop.category', $cat->slug) }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.catalog', ['filter' => 'preorder']) }}">
                            Precompras <span class="badge bg-warning text-dark ms-1">NEW</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.catalog', ['filter' => 'offers']) }}">Ofertas</a>
                    </li>
                </ul>

                {{-- Carrito desktop --}}
                <a href="{{ route('cart.index') }}" class="btn btn-warning fw-bold position-relative d-none d-lg-inline-flex align-items-center gap-2">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span>Carrito</span>
                    @if(($cartCount ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

        </div>
    </nav>

    {{-- ─── NIVEL 3: BARRA DE FRANQUICIAS (iconos) ────────────────────────── --}}
    <div class="franchise-bar bg-white border-bottom shadow-sm d-none d-lg-block">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-4 py-2 overflow-auto franchise-scroll">
                <a href="{{ route('shop.catalog') }}" class="franchise-item text-center text-decoration-none {{ !request()->route('slug') ? 'active' : '' }}">
                    <span class="franchise-icon"><i class="bi bi-grid-fill"></i></span>
                    <span class="franchise-label d-block small fw-semibold text-dark">Todo</span>
                </a>
                @foreach($headerFranchises ?? [] as $franchise)
                    <a href="{{ route('shop.franchise', $franchise->slug) }}"
                       class="franchise-item text-center text-decoration-none {{ request()->route('slug') === $franchise->slug ? 'active' : '' }}"
                       title="{{ $franchise->name }}">
                        @if($franchise->icon_url)
                            <img src="{{ asset($franchise->icon_url) }}" alt="{{ $franchise->name }}" class="franchise-icon-img">
                        @else
                            <span class="franchise-icon"><i class="bi bi-collection-fill"></i></span>
                        @endif
                        <span class="franchise-label d-block small fw-semibold text-dark">{{ $franchise->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

</header>

{{-- ============================================================
     MENÚ MÓVIL OFFCANVAS (Drawer)
============================================================ --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title fw-bold" id="mobileMenuLabel">
            <span class="text-warning">Factory</span> Cards
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body p-0">

        {{-- Buscador móvil --}}
        <div class="p-3 bg-light border-bottom">
            <form action="{{ route('shop.search') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="q" class="form-control" placeholder="Buscar productos..." value="{{ request('q') }}">
                <button class="btn btn-warning px-3" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        {{-- Navegación móvil --}}
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                    <i class="bi bi-house-fill text-primary"></i> Inicio
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('shop.catalog') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                    <i class="bi bi-shop text-primary"></i> Ver toda la tienda
                </a>
            </li>

            {{-- Franquicias --}}
            <li class="list-group-item bg-light">
                <span class="small fw-bold text-muted text-uppercase">Franquicias</span>
            </li>
            @foreach($headerFranchises ?? [] as $franchise)
                <li class="list-group-item">
                    <a href="{{ route('shop.franchise', $franchise->slug) }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        @if($franchise->icon_url)
                            <img src="{{ asset($franchise->icon_url) }}" alt="" style="width:20px;height:20px;object-fit:contain;">
                        @else
                            <i class="bi bi-collection-fill text-primary"></i>
                        @endif
                        {{ $franchise->name }}
                    </a>
                </li>
            @endforeach

            {{-- Categorías --}}
            <li class="list-group-item bg-light">
                <span class="small fw-bold text-muted text-uppercase">Categorías</span>
            </li>
            @foreach($headerCategories ?? [] as $cat)
                <li class="list-group-item">
                    <a href="{{ route('shop.category', $cat->slug) }}"
                       class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-tag-fill text-primary"></i> {{ $cat->name }}
                    </a>
                </li>
            @endforeach

            <li class="list-group-item bg-light">
                <span class="small fw-bold text-muted text-uppercase">Mi cuenta</span>
            </li>
            @auth
                <li class="list-group-item">
                    <a href="{{ route('user.dashboard') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person-circle text-primary"></i> {{ auth()->user()->name }}
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('user.orders') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-bag-check-fill text-primary"></i> Mis pedidos
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                    <li class="list-group-item">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-warning text-decoration-none py-1 fw-semibold">
                            <i class="bi bi-speedometer2"></i> Panel Admin
                        </a>
                    </li>
                @endif
                <li class="list-group-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 d-flex align-items-center gap-2 text-danger text-decoration-none">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </button>
                    </form>
                </li>
            @else
                <li class="list-group-item">
                    <a href="{{ route('login') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person text-primary"></i> Iniciar sesión
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('register') }}" class="d-flex align-items-center gap-2 text-dark text-decoration-none py-1">
                        <i class="bi bi-person-plus text-primary"></i> Registrarse
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</div>

{{-- ============================================================
     TRUST BADGES (Barra de confianza)
============================================================ --}}
<div class="trust-bar bg-light border-bottom py-2">
    <div class="container">
        <div class="row text-center g-2">
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2 small">
                    <i class="bi bi-truck text-primary fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold lh-1 small">Envío Gratis</div>
                        <div class="text-muted" style="font-size:.72rem">En pedidos +50€</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2 small">
                    <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold lh-1 small">Pago Seguro</div>
                        <div class="text-muted" style="font-size:.72rem">SSL + Stripe / TPV</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2 small">
                    <i class="bi bi-arrow-return-left text-warning fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold lh-1 small">Devoluciones</div>
                        <div class="text-muted" style="font-size:.72rem">14 días sin problema</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2 small">
                    <i class="bi bi-headset text-danger fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold lh-1 small">Soporte</div>
                        <div class="text-muted" style="font-size:.72rem">Lunes a Sábado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     CONTENIDO PRINCIPAL
============================================================ --}}
<main id="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0 rounded-0 border-0 text-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 border-0 text-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

{{-- ============================================================
     FOOTER
============================================================ --}}
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">

            {{-- Col 1: Marca y descripción --}}
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
                <h6 class="text-uppercase fw-bold text-warning mb-3 small">Franquicias</h6>
                <ul class="list-unstyled small">
                    @foreach($headerFranchises ?? [] as $franchise)
                        <li class="mb-1">
                            <a href="{{ route('shop.franchise', $franchise->slug) }}" class="text-muted text-decoration-none footer-link">
                                {{ $franchise->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3: Información --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold text-warning mb-3 small">Información</h6>
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
                <h6 class="text-uppercase fw-bold text-warning mb-3 small">Mi cuenta</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><a href="{{ route('login') }}" class="text-muted text-decoration-none footer-link">Iniciar sesión</a></li>
                    <li class="mb-1"><a href="{{ route('register') }}" class="text-muted text-decoration-none footer-link">Registrarse</a></li>
                    <li class="mb-1"><a href="{{ route('user.orders') }}" class="text-muted text-decoration-none footer-link">Mis pedidos</a></li>
                    <li class="mb-1"><a href="{{ route('cart.index') }}" class="text-muted text-decoration-none footer-link">Carrito</a></li>
                </ul>
            </div>

            {{-- Col 5: Métodos de pago --}}
            <div class="col-6 col-md-2">
                <h6 class="text-uppercase fw-bold text-warning mb-3 small">Pago seguro</h6>
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

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- JS propio --}}
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>
