<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Verifica que el usuario autenticado tenga el rol 'admin'.
     * Si no está autenticado, redirige al login.
     * Si está autenticado pero no es admin, devuelve 403.
     * Registramos los intentos de acceso no autorizado para auditoría.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está autenticado, redirigir al login (Breeze lo gestiona automáticamente
        // a través del middleware 'auth' que se aplica antes que éste)
        if (! auth()->check()) {
            Log::warning('Intento de acceso al panel admin sin autenticación', [
                'ip'  => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login');
        }

        $usuario = auth()->user();

        // Si el usuario no tiene rol admin, registrar el intento y denegar acceso
        if (! $usuario->isAdmin()) {
            Log::warning('Intento de acceso al panel admin denegado', [
                'usuario_id'    => $usuario->id,
                'usuario_email' => $usuario->email,
                'rol'           => $usuario->role,
                'ip'            => $request->ip(),
                'url'           => $request->fullUrl(),
            ]);

            abort(403, 'Acceso restringido al panel de administración.');
        }

        // Acceso autorizado — registrar solo en modo debug para no saturar logs
        Log::debug('Acceso al panel admin autorizado', [
            'usuario_id'    => $usuario->id,
            'usuario_email' => $usuario->email,
        ]);

        return $next($request);
    }
}
