<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Factory Cards</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --sidebar-w: 240px; }
        body { background: #f0f2f5; }
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: #1a1d23;
            position: fixed; top: 0; left: 0; z-index: 100; display: flex; flex-direction: column;
        }
        .sidebar .brand {
            padding: 1.25rem 1rem; background: #111318; color: #fff;
            font-weight: 700; font-size: 1.1rem; text-decoration: none;
            display: flex; align-items: center; gap: .5rem;
        }
        .sidebar .brand span { color: #f59e0b; }
        .sidebar .nav-link {
            color: #adb5bd; padding: .6rem 1.25rem; border-radius: 6px;
            margin: 2px 8px; display: flex; align-items: center; gap: .6rem;
            font-size: .9rem; transition: background .15s, color .15s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #2d3139; color: #fff;
        }
        .sidebar .nav-section {
            font-size: .7rem; text-transform: uppercase; color: #6c757d;
            padding: .75rem 1.25rem .25rem; letter-spacing: .08em;
        }
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }
        .topbar {
            background: #fff; border-bottom: 1px solid #dee2e6;
            padding: .75rem 1.5rem; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 99;
        }
        .page-body { padding: 1.5rem; }
        .stat-card { border: none; border-radius: 12px; }
        .stat-card .card-body { padding: 1.25rem; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<nav class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand">
        <i class="bi bi-shop"></i> Factory <span>Cards</span>
    </a>

    <div class="flex-grow-1 py-2">
        <div class="nav-section">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section mt-2">Catálogo</div>
        <a href="{{ route('admin.productos.index') }}" class="nav-link @if(request()->routeIs('admin.productos.*')) active @endif">
            <i class="bi bi-box-seam"></i> Productos
        </a>
        <a href="{{ route('admin.categorias.index') }}" class="nav-link @if(request()->routeIs('admin.categorias.*')) active @endif">
            <i class="bi bi-folder2"></i> Categorías
        </a>
        <a href="{{ route('admin.franquicias.index') }}" class="nav-link @if(request()->routeIs('admin.franquicias.*')) active @endif">
            <i class="bi bi-trophy"></i> Franquicias
        </a>

        <div class="nav-section mt-2">Contenido</div>
        <a href="{{ route('admin.banners.index') }}" class="nav-link @if(request()->routeIs('admin.banners.*')) active @endif">
            <i class="bi bi-image"></i> Banners
        </a>

        <div class="nav-section mt-2">Ventas</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-link @if(request()->routeIs('admin.orders.*')) active @endif">
            <i class="bi bi-receipt"></i> Pedidos
        </a>
    </div>

    <div class="p-3">
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="bi bi-arrow-up-right-square"></i> Ver tienda
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 border-0 text-start bg-transparent">
                <i class="bi bi-box-arrow-left"></i> Cerrar sesión
            </button>
        </form>
    </div>
</nav>

{{-- Main --}}
<div class="main-content">
    <div class="topbar">
        <h6 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">{{ auth()->user()->name }}</span>
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-weight:700;font-size:.85rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <div class="page-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
