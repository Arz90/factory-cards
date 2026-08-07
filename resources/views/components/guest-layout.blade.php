{{--
    Componente: guest-layout
    Propósito: Layout de página completa para vistas de autenticación (login, registro,
    recuperar contraseña). Usa Bootstrap 5 para coherencia visual con la tienda.
    Todas las páginas guest muestran un panel centrado con el logo de la tienda.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acceso') — Factory Cards</title>

    {{-- Bootstrap 5 CSS desde CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Fondo degradado sutil para las páginas de acceso */
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Tarjeta principal del formulario */
        .tarjeta-auth {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Cabecera de la tarjeta con el logo */
        .cabecera-auth {
            background: linear-gradient(135deg, #1a1d23 0%, #2d3139 100%);
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
        }

        /* Nombre de la tienda en la cabecera */
        .nombre-tienda {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .nombre-tienda span {
            color: #f59e0b; /* Acento dorado de Factory Cards */
        }

        /* Cuerpo del formulario */
        .cuerpo-auth {
            padding: 2rem;
            background: #fff;
            border-radius: 0 0 16px 16px;
        }
    </style>
</head>
<body>

    <div class="tarjeta-auth card">
        {{-- Cabecera con logo de la tienda --}}
        <div class="cabecera-auth">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="nombre-tienda">
                    <i class="bi bi-shop me-2"></i>Factory <span>Cards</span>
                </div>
            </a>
        </div>

        {{-- Contenido del formulario (inyectado por cada vista auth) --}}
        <div class="cuerpo-auth">
            {{ $slot }}
        </div>
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
