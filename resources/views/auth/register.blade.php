{{--
    Vista: auth/register
    Propósito: Formulario de registro de nuevos clientes.
    Los usuarios nuevos reciben el rol 'customer' por defecto (definido en el modelo User).
--}}
<x-guest-layout>

    <h5 class="fw-bold mb-1 text-center">Crear cuenta</h5>
    <p class="text-muted text-center small mb-4">Únete a Factory Cards</p>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Campo: Nombre --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-medium">Nombre completo</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Tu nombre"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

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
                    autocomplete="username"
                    placeholder="tu@email.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Campo: Contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label fw-medium">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Campo: Confirmar contraseña --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-medium">Confirmar contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required
                    autocomplete="new-password"
                    placeholder="Repite la contraseña"
                >
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-person-plus me-2"></i>Crear cuenta
            </button>
        </div>
    </form>

    <hr class="my-3">
    <p class="text-center text-muted small mb-0">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Inicia sesión</a>
    </p>

</x-guest-layout>
