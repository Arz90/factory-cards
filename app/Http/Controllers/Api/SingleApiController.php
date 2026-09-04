<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Single;
use Illuminate\Http\Request;

class SingleApiController extends Controller
{
    /**
     * Búsqueda de singles por nombre.
     * Devuelve hasta 10 resultados activos que coincidan con el parámetro `q`.
     * Usado por el autocompletado del buscador de "Vende tus cartas".
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $singles = Single::active()
            ->with('franchise:id,name,color')
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(10)
            ->get([
                'id',
                'franchise_id',
                'name',
                'set_name',
                'card_number',
                'rarity',
                'buy_price_cash',
                'buy_price_credit',
                'image_url',
            ]);

        return response()->json(['data' => $singles]);
    }
}
