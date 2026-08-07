{{--
    Vista: auth/forgot-password
    Propósito: Formulario para solicitar un enlace de restablecimiento de contraseña
    por email. Laravel gestiona internamente el envío del email y la expiración del token.
--}}
<x-guest-layout>

    <h5 class="fw-bold mb-1 text-center">Recuperar contraseña</h5>
    <p class="text-muted text-center small mb-4">
        Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    {{-- Confirmación de envío del email --}}
    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
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
                    placeholder="tu@email.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-send me-2"></i>Enviar enlace
            </button>
        </div>
    </form>

    <hr class="my-3">
    <p class="text-center text-muted small mb-0">
        <a href="{{ route('login') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión
        </a>
    </p>

</x-guest-layout>
