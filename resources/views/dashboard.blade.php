{{--
    Vista: dashboard (stub de Breeze)
    Propósito: Breeze redirige aquí tras el login. Redirigimos inmediatamente
    al panel correcto según el rol del usuario (admin → /admin, customer → /mi-cuenta).
    Usamos meta-refresh como fallback por si el JS no ejecuta.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url={{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}">
    <title>Redirigiendo — Factory Cards</title>
</head>
<body>
    <script>
        // Redirige al panel correcto según el rol del usuario
        window.location.href = "{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}";
    </script>
</body>
</html>
