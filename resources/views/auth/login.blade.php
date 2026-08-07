{{--
    Vista: auth/login
    Propósito: Formulario de inicio de sesión para administradores y clientes.
    Usa el componente guest-layout con Bootstrap 5.
    El middleware 'admin' redirige a /admin tras autenticarse si el rol es admin.
--}}
<x-guest-layout>

    {{-- Título del formulario --}}
    <h5 class="fw-bold mb-1 text-center">Iniciar sesión</h5>
    <p class="text-muted text-center small mb-4">Accede a tu cuenta de Factory Cards</p>

    {{-- Mensaje de estado de sesión (ej: "enlace de restablecimiento enviado") --}}
    @if (session('status'))
        <div class="alert alert-success alert-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Campo: Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="admin@factorycards.com"
                >
            </div>
        </div>

        {{-- Campo: Contraseña --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label fw-medium mb-0">Contraseña</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-muted">
                        ¿Olvidaste la contraseña?
                    </a>
                @endif
            </div>
            <div class="input-group mt-1">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
            </div>
        </div>

        {{-- Opción: Recordarme --}}
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label small text-muted" for="remember_me">
                Mantener sesión iniciada
            </label>
        </div>

        {{-- Botón de envío --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
        </div>
    </form>

    {{-- Enlace a registro --}}
    <hr class="my-3">
    <p class="text-center text-muted small mb-0">
        ¿Aún no tienes cuenta?
        <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Regístrate</a>
    </p>

</x-guest-layout>
