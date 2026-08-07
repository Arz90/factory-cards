{{--
    Vista: auth/reset-password
    Propósito: Formulario para establecer una nueva contraseña usando el token
    enviado por email. El token se pasa como campo oculto.
--}}
<x-guest-layout>

    <h5 class="fw-bold mb-1 text-center">Nueva contraseña</h5>
    <p class="text-muted text-center small mb-4">Introduce tu nueva contraseña de acceso.</p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    {{-- URL relativa para evitar mismatch de dominio con la cookie de sesión --}}
    <form method="POST" action="{{ route('password.store', absolute: false) }}">
        @csrf

        {{-- Token de restablecimiento (oculto, enviado por email) --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    autofocus
                    autocomplete="username"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-medium">Nueva contraseña</label>
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
                >
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle me-2"></i>Restablecer contraseña
            </button>
        </div>
    </form>

</x-guest-layout>
