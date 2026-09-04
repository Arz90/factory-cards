<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Single;
use App\Services\TcgImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Panel de administración de Singles (cartas individuales).
 * Permite al admin ver el estado de la tabla y lanzar sincronizaciones
 * con la API de Pokémon TCG bajo demanda.
 */
class SingleController extends Controller
{
    /**
     * Panel principal: estadísticas y formulario de sincronización.
     */
    public function index()
    {
        $stats = [
            'total'    => Single::count(),
            'activas'  => Single::where('is_active', true)->count(),
            'sin_precio' => Single::where('buy_price_cash', '<=', 0.02)->count(),
        ];

        // Últimas 20 singles importadas/actualizadas
        $recientes = Single::with('franchise:id,name')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        Log::info('Admin accede al panel de Singles.', ['usuario_id' => auth()->id()]);

        return view('admin.singles.index', compact('stats', 'recientes'));
    }

    /**
     * Lanza la sincronización de un set específico y redirige con resultado.
     */
    public function sync(Request $request, TcgImportService $servicio)
    {
        $request->validate([
            'set_id' => ['required', 'string', 'max:30', 'regex:/^[a-z0-9]+$/i'],
        ]);

        $setId = trim($request->set_id);

        Log::info("Admin lanza sync manual de singles.", [
            'usuario_id' => auth()->id(),
            'set_id'     => $setId,
        ]);

        $resultado = $servicio->syncSet($setId);

        $mensaje = "Set <strong>{$setId}</strong> sincronizado: "
            . "{$resultado['importadas']} importadas, "
            . "{$resultado['actualizadas']} actualizadas"
            . ($resultado['errores'] ? ", {$resultado['errores']} errores." : '.');

        if ($resultado['errores'] > 0 && $resultado['importadas'] === 0 && $resultado['actualizadas'] === 0) {
            return redirect()->route('admin.singles.index')
                ->with('error', "Error al sincronizar el set <strong>{$setId}</strong>. Comprueba el ID e inténtalo de nuevo.");
        }

        return redirect()->route('admin.singles.index')->with('success', $mensaje);
    }
}
