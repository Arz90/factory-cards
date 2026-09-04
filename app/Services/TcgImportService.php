<?php

namespace App\Services;

use App\Models\Franchise;
use App\Models\Single;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de sincronización de Singles desde la API de Pokémon TCG (pokemontcg.io).
 *
 * Uso desde código:
 *   app(TcgImportService::class)->syncSet('sv3pt5');
 *
 * La API devuelve hasta 250 cartas por página. El servicio itera
 * automáticamente si hay más páginas disponibles.
 *
 * Precio de compra:
 *   - buy_price_cash   = precio medio Cardmarket × 0.50  (50% del mercado)
 *   - buy_price_credit = buy_price_cash × 1.20           (+20% saldo de tienda)
 *   - Precio mínimo garantizado: 0.02 € en efectivo
 */
class TcgImportService
{
    /** URL base de la API pública de Pokémon TCG */
    private const API_BASE = 'https://api.pokemontcg.io/v2';

    /** Factor de compra sobre el precio de mercado Cardmarket */
    private const FACTOR_CASH   = 0.50;
    private const FACTOR_CREDIT = 0.60;   // = cash × 1.20

    /** Precio mínimo para no bajar de 0 */
    private const PRECIO_MINIMO_CASH   = 0.02;
    private const PRECIO_MINIMO_CREDIT = 0.03;

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Sincroniza todas las cartas de un set dado.
     *
     * @param  string $setId  ID del set en la API (ej: "sv3pt5", "base1")
     * @return array  ['importadas' => int, 'actualizadas' => int, 'errores' => int]
     */
    public function syncSet(string $setId): array
    {
        $franquicia = Franchise::where('slug', 'pokemon')
            ->orWhere('name', 'like', '%okémon%')
            ->first();

        if (!$franquicia) {
            Log::error('[TcgImport] No se encontró la franquicia Pokémon en la BD.');
            return ['importadas' => 0, 'actualizadas' => 0, 'errores' => 1];
        }

        $stats    = ['importadas' => 0, 'actualizadas' => 0, 'errores' => 0];
        $pagina   = 1;
        $tamPagina = 250;

        do {
            $respuesta = Http::timeout(30)
                ->withoutVerifying()   // SSL desactivado en local Windows (cURL error 60)
                ->withHeaders($this->headers())
                ->get(self::API_BASE . '/cards', [
                    'q'        => "set.id:{$setId}",
                    'page'     => $pagina,
                    'pageSize' => $tamPagina,
                    'orderBy'  => 'number',
                    'select'   => 'id,name,number,rarity,set,images,cardmarket',
                ]);

            if (!$respuesta->successful()) {
                Log::error("[TcgImport] Error HTTP {$respuesta->status()} al consultar set {$setId} página {$pagina}.");
                $stats['errores']++;
                break;
            }

            $datos     = $respuesta->json();
            $cartas    = $datos['data'] ?? [];
            $totalApi  = $datos['totalCount'] ?? count($cartas);

            foreach ($cartas as $carta) {
                try {
                    [$cash, $credit] = $this->calcularPrecios($carta);
                    $setNombre = $carta['set']['name'] ?? $setId;
                    $numero    = $carta['number'] ?? null;
                    $total     = $carta['set']['total'] ?? null;
                    $numFull   = ($numero && $total) ? "{$numero}/{$total}" : $numero;

                    $resultado = Single::updateOrCreate(
                        // Clave de búsqueda para evitar duplicados
                        [
                            'set_name'    => $setNombre,
                            'card_number' => $numFull,
                        ],
                        // Campos a crear/actualizar
                        [
                            'franchise_id'     => $franquicia->id,
                            'name'             => $carta['name'],
                            'rarity'           => $carta['rarity'] ?? null,
                            'buy_price_cash'   => $cash,
                            'buy_price_credit' => $credit,
                            'image_url'        => $carta['images']['small'] ?? null,
                            'is_active'        => true,
                        ]
                    );

                    $resultado->wasRecentlyCreated
                        ? $stats['importadas']++
                        : $stats['actualizadas']++;

                } catch (\Throwable $e) {
                    Log::error('[TcgImport] Error procesando carta: ' . $e->getMessage(), [
                        'carta' => $carta['id'] ?? 'desconocida',
                    ]);
                    $stats['errores']++;
                }
            }

            $pagina++;
            $hayMasPaginas = ($pagina - 1) * $tamPagina < $totalApi && count($cartas) === $tamPagina;

        } while ($hayMasPaginas);

        Log::info("[TcgImport] Set {$setId} sincronizado.", $stats);

        return $stats;
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Calcula buy_price_cash y buy_price_credit a partir de los datos de Cardmarket.
     * Usa el precio promedio de venta. Si no está disponible, aplica precio mínimo.
     *
     * @return array [cash, credit]
     */
    private function calcularPrecios(array $carta): array
    {
        $precioMercado = null;

        // Intenta precio Cardmarket (mercado europeo, más relevante para España)
        $cm = $carta['cardmarket']['prices'] ?? [];
        foreach (['averageSellPrice', 'avg1', 'avg7', 'avg30'] as $campo) {
            if (!empty($cm[$campo]) && $cm[$campo] > 0) {
                $precioMercado = (float) $cm[$campo];
                break;
            }
        }

        if ($precioMercado === null || $precioMercado <= 0) {
            return [self::PRECIO_MINIMO_CASH, self::PRECIO_MINIMO_CREDIT];
        }

        $cash   = max(round($precioMercado * self::FACTOR_CASH, 2), self::PRECIO_MINIMO_CASH);
        $credit = max(round($precioMercado * self::FACTOR_CREDIT, 2), self::PRECIO_MINIMO_CREDIT);

        return [$cash, $credit];
    }

    /**
     * Cabeceras HTTP para la API. Incluye API key si está configurada en .env.
     */
    private function headers(): array
    {
        $headers = ['Accept' => 'application/json'];

        if ($apiKey = config('services.pokemontcg.key')) {
            $headers['X-Api-Key'] = $apiKey;
        }

        return $headers;
    }
}
