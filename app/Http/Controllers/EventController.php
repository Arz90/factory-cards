<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Muestra la página pública de eventos con el calendario interactivo FullCalendar.
     * Pasa los eventos activos tanto para la lista como en formato JSON para el calendario.
     */
    public function index()
    {
        try {
            $eventos = Event::activos()->proximos()->get();
        } catch (\Exception $e) {
            Log::error('Error al cargar eventos públicos', ['error' => $e->getMessage()]);
            $eventos = collect();
        }

        // Construir el array JSON que consume FullCalendar
        $eventosCalendario = $eventos->map(function (Event $evento) {
            return [
                'id'              => $evento->id,
                'title'           => $evento->title,
                'start'           => $evento->start_date->toIso8601String(),
                'end'             => $evento->end_date?->toIso8601String(),
                'backgroundColor' => '#29A44F',
                'borderColor'     => '#1D7A39',
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'description'     => $evento->description,
                    'precio'          => $evento->precioFormateado(),
                    'image_url'       => $evento->urlImagen(),
                    'google_maps_url' => $evento->google_maps_url,
                    'start_formato'   => $evento->start_date->format('d/m/Y H:i'),
                    'end_formato'     => $evento->end_date?->format('d/m/Y H:i'),
                ],
            ];
        });

        return view('events.index', compact('eventos', 'eventosCalendario'));
    }
}
