<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use Illuminate\Http\Request;

class SellCardsController extends Controller
{
    /**
     * Muestra la landing page "Vende tus cartas".
     * Pasa las franquicias activas para el desplegable dinámico del formulario.
     */
    public function index()
    {
        $franquicias = Franchise::active()->get();

        return view('sell-cards', compact('franquicias'));
    }

    /**
     * Procesa el envío del formulario de venta.
     * Por ahora redirige con mensaje de éxito; en fases futuras enviará email y/o crea registro.
     */
    public function store(Request $request)
    {
        // TODO Phase 3: validar, guardar registro SellRequest, enviar email al admin.
        return redirect()->route('sell-cards.index')
            ->with('success', '¡Gracias! Hemos recibido tu solicitud. Te contactaremos en menos de 48 horas con la tasación de tu colección.');
    }
}
