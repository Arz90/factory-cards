{{--
    Vista: auth/verify-email
    Propósito: Informa al usuario que debe verificar su email antes de continuar.
    Permite reenviar el email de verificación o cerrar sesión.
--}}
<x-guest-layout>

    <h5 class="fw-bold mb-1 text-center">Verifica tu email</h5>

    <p class="text-muted text-center small mb-4">
        Gracias por registrarte. Antes de continuar, verifica tu dirección de email
        haciendo clic en el enlace que te hemos enviado.
        <br>Si no lo recibiste, podemos enviarte otro.
    </p>

    {{-- Confirmación de reenvío --}}
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            Se ha enviado un nuevo enlace de verificación a tu email.
        </div>
    @endif

    <div class="d-grid gap-2">
        {{-- Botón para reenviar el email de verificación --}}
        {{-- URL relativa para evitar mismatch de dominio con la cookie de sesión --}}
        <form method="POST" action="{{ route('verification.send', absolute: false) }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-envelope-check me-2"></i>Reenviar email de verificación
            </button>
        </form>

        {{-- Opción para cerrar sesión --}}
        <form method="POST" action="{{ route('logout', absolute: false) }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-left me-2"></i>Cerrar sesión
            </button>
        </form>
    </div>

</x-guest-layout>
