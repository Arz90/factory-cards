{{--
    Vista: auth/confirm-password
    Propósito: Pide al usuario reconfirmar su contraseña antes de acceder a
    zonas sensibles. Laravel marca la sesión como "confirmada" por un tiempo limitado.
--}}
<x-guest-layout>

    <h5 class="fw-bold mb-1 text-center">Confirmar acceso</h5>
    <p class="text-muted text-center small mb-4">
        Esta es un área segura. Confirma tu contraseña antes de continuar.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    {{-- URL relativa para evitar mismatch de dominio con la cookie de sesión --}}
    <form method="POST" action="{{ route('password.confirm', absolute: false) }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label fw-medium">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="Tu contraseña actual"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-shield-check me-2"></i>Confirmar
            </button>
        </div>
    </form>

</x-guest-layout>
