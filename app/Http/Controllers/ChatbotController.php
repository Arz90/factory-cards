<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    /**
     * Devuelve los 3 próximos eventos activos en formato JSON
     * para mostrarlos en la opción rápida "Próximos Torneos" del chatbot.
     */
    public function eventosProximos(): JsonResponse
    {
        $eventos = Event::activos()
            ->proximos()
            ->where('start_date', '>=', now())
            ->limit(3)
            ->get(['title', 'start_date', 'end_date', 'price'])
            ->map(fn ($e) => [
                'titulo'  => $e->title,
                'fecha'   => $e->start_date->translatedFormat('j \d\e F, H:i') . 'h',
                'precio'  => $e->precioFormateado(),
            ]);

        return response()->json(['eventos' => $eventos]);
    }

    /**
     * Recibe el formulario de contacto del chatbot (nombre, email, mensaje)
     * y lo guarda en el log de Laravel para revisión posterior.
     * Devuelve JSON para que el JS lo procese sin recargar página.
     */
    public function enviarMensaje(Request $request): JsonResponse
    {
        // Validación básica sin usar 'image' ni 'mimes' (fileinfo bloqueado en este entorno)
        $datos = $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'mensaje' => 'required|string|max:1000',
        ]);

        // Guardar en el log de Laravel hasta que se implemente un sistema de tickets
        Log::info('Chatbot — nuevo mensaje de contacto', [
            'nombre'  => $datos['nombre'],
            'email'   => $datos['email'],
            'mensaje' => $datos['mensaje'],
            'ip'      => $request->ip(),
        ]);

        return response()->json([
            'ok'      => true,
            'mensaje' => '¡Gracias, ' . $datos['nombre'] . '! Te responderemos a ' . $datos['email'] . ' lo antes posible.',
        ]);
    }
}
