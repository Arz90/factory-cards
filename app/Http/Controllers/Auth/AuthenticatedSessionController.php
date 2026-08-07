<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el login y redirige según el rol del usuario:
     * - Admin → /admin (panel de administración)
     * - Cliente → / (portada de la tienda)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $usuario = Auth::user();

        // Determinar destino según el rol del usuario
        if ($usuario->isAdmin()) {
            $destino = route('admin.dashboard');
            Log::info('Login admin: redirigiendo al panel de administración', [
                'usuario_id'    => $usuario->id,
                'usuario_email' => $usuario->email,
            ]);
        } else {
            $destino = route('home');
            Log::info('Login cliente: redirigiendo a la portada', [
                'usuario_id'    => $usuario->id,
                'usuario_email' => $usuario->email,
            ]);
        }

        return redirect()->intended($destino);
    }

    /**
     * Cierra la sesión del usuario y redirige a la portada.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Sesión cerrada', ['usuario_id' => $usuario?->id]);

        return redirect('/');
    }
}
