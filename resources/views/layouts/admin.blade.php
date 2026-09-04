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
        :root { --sidebar-w: 260px; }
        body { background: #f0f2f5; }

        /* ── Sidebar base ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #1a1d23;
            display: flex;
            flex-direction: column;
        }
        .sidebar .brand {
            padding: 1.25rem 1rem; background: #111318; color: #fff;
            font-weight: 700; font-size: 1.1rem; text-decoration: none;
            display: flex; align-items: center; gap: .5rem;
        }
        .sidebar .brand span { color: #f59e0b; }
        .sidebar .nav-link {
            color: #adb5bd; padding: .65rem 1.25rem; border-radius: 6px;
            margin: 2px 8px; display: flex; align-items: center; gap: .6rem;
            font-size: .9rem; transition: background .15s, color .15s;
            /* Área táctil mínima recomendada para móvil */
            min-height: 44px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #2d3139; color: #fff;
        }
        .sidebar .nav-section {
            font-size: .7rem; text-transform: uppercase; color: #6c757d;
            padding: .75rem 1.25rem .25rem; letter-spacing: .08em;
        }

        /* ── Desktop (≥992px): layout flex — sidebar + contenido uno al lado del otro ──
           Evita el conflicto entre position:fixed y el z-index alto de Bootstrap offcanvas.
           La sidebar usa position:sticky para quedar pegada al scroll sin salirse del flujo. ── */
        @media (min-width: 992px) {
            body {
                display: flex;
                min-height: 100vh;
                align-items: flex-start;
            }
            .sidebar {
                position: sticky !important;
                top: 0 !important;
                width: var(--sidebar-w) !important;
                min-width: var(--sidebar-w) !important;
                height: 100vh !important;
                flex-shrink: 0;
                /* Anular Bootstrap offcanvas */
                transform: none !important;
                visibility: visible !important;
            }
            .main-content {
                flex: 1;
                min-width: 0;   /* evita que el contenido desborde el flex */
                margin-left: 0; /* el flex ya posiciona, no hace falta margen */
                width: auto;
            }
            .admin-menu-toggle { display: none !important; }
        }

        /* ── Móvil y tablet (<992px): sidebar como offcanvas, main a ancho completo ── */
        @media (max-width: 991.98px) {
            .main-content { margin-left: 0; width: 100%; }
            .page-body { padding: 1rem; }
            /* Botones de acción con área táctil cómoda en móvil */
            .btn-sm { min-height: 38px; min-width: 38px; }
        }

        /* ── Topbar ── */
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

{{-- Sidebar — en móvil actúa como offcanvas Bootstrap; en desktop queda fija via CSS --}}
<nav class="sidebar offcanvas offcanvas-start"
     tabindex="-1"
     id="adminSidebar"
     aria-labelledby="adminSidebarLabel">

    <a href="{{ route('admin.dashboard') }}" class="brand" id="adminSidebarLabel">
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
        <a href="{{ route('admin.promo-banners.index') }}" class="nav-link @if(request()->routeIs('admin.promo-banners.*')) active @endif">
            <i class="bi bi-layout-split"></i> Promo Banner
        </a>
        <a href="{{ route('admin.eventos.index') }}" class="nav-link @if(request()->routeIs('admin.eventos.*')) active @endif">
            <i class="bi bi-calendar-event"></i> Eventos
        </a>
        <a href="{{ route('admin.singles.index') }}" class="nav-link @if(request()->routeIs('admin.singles.*')) active @endif">
            <i class="bi bi-card-list"></i> Singles / API
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
        <div class="d-flex align-items-center gap-3">
            {{-- Botón hamburguesa — solo visible en móvil, abre el sidebar como offcanvas --}}
            <button class="btn btn-outline-secondary border-0 admin-menu-toggle p-1"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#adminSidebar"
                    aria-controls="adminSidebar"
                    aria-label="Abrir menú">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h6 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h6>
        </div>
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

{{-- SweetAlert2: popups de confirmación elegantes para acciones destructivas --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
/**
 * Listener global para confirmación de eliminación con SweetAlert2.
 *
 * Uso en cualquier vista del panel admin:
 *   <form method="POST" action="..." class="d-inline">
 *       @csrf  @method('DELETE')
 *       <button type="button"
 *               class="btn-swal-delete"
 *               data-nombre="Nombre del elemento">
 *           <i class="bi bi-trash"></i>
 *       </button>
 *   </form>
 *
 * El botón debe ser type="button" (no submit) para que SweetAlert intercepte el clic.
 * Si el usuario confirma, el listener envía el formulario padre con form.submit().
 */
document.addEventListener('DOMContentLoaded', function () {

    // Usamos delegación de eventos para que funcione con elementos cargados dinámicamente
    document.addEventListener('click', function (e) {
        const boton = e.target.closest('.btn-swal-delete');
        if (!boton) return;

        e.preventDefault();

        // El nombre del elemento a eliminar viene del atributo data-nombre
        const nombreElemento = boton.dataset.nombre
            ? '"' + boton.dataset.nombre + '"'
            : 'este elemento';

        // Formulario padre que contiene la acción DELETE
        const formulario = boton.closest('form');
        if (!formulario) {
            console.error('btn-swal-delete: no se encontró el <form> padre.');
            return;
        }

        // Mostrar el popup de confirmación
        Swal.fire({
            title:              '¿Estás seguro?',
            text:               '¿Estás seguro de que deseas eliminar ' + nombreElemento + '? No podrás revertir este cambio.',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor:  '#6c757d',
            confirmButtonText:  '<i class="bi bi-trash me-1"></i>Sí, eliminar',
            cancelButtonText:   'Cancelar',
            reverseButtons:     true,
            focusCancel:        true,   // foco en Cancelar por defecto (más seguro)
        }).then(function (resultado) {
            if (resultado.isConfirmed) {
                // El usuario confirmó: enviamos el formulario DELETE
                formulario.submit();
            }
        });
    });

});
</script>

@stack('scripts')
</body>
</html>
