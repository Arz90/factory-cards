<?php

namespace App\Console\Commands;

use App\Services\TcgImportService;
use Illuminate\Console\Command;

class SyncSingles extends Command
{
    /**
     * Firma del comando.
     * Uso: php artisan singles:sync --set=sv3pt5
     * Sin --set sincroniza los sets activos por defecto.
     */
    protected $signature = 'singles:sync
                            {--set= : ID del set en pokemontcg.io (ej: sv3pt5, base1)}
                            {--sets= : Varios sets separados por coma (ej: sv3pt5,sv4)}';

    protected $description = 'Sincroniza cartas individuales (singles) desde la API de Pokémon TCG.';

    /** Sets activos por defecto si no se especifica ninguno */
    private const SETS_POR_DEFECTO = ['sv8pt5', 'sv8', 'sv7', 'sv6pt5', 'sv6'];

    public function handle(TcgImportService $servicio): int
    {
        $sets = $this->resolverSets();

        $this->info("Sincronizando " . count($sets) . " set(s): " . implode(', ', $sets));
        $this->newLine();

        $totalImportadas  = 0;
        $totalActualizadas = 0;
        $totalErrores     = 0;

        foreach ($sets as $setId) {
            $this->line("<fg=yellow>▶ Set:</> <fg=cyan>{$setId}</>");

            $stats = $servicio->syncSet($setId);

            $this->line(
                "  <fg=green>✓ Importadas:</> {$stats['importadas']}  " .
                "<fg=blue>↻ Actualizadas:</> {$stats['actualizadas']}  " .
                ($stats['errores'] ? "<fg=red>✗ Errores:</> {$stats['errores']}" : '')
            );

            $totalImportadas  += $stats['importadas'];
            $totalActualizadas += $stats['actualizadas'];
            $totalErrores     += $stats['errores'];
        }

        $this->newLine();
        $this->table(
            ['Set', 'Importadas', 'Actualizadas', 'Errores'],
            [[implode(', ', $sets), $totalImportadas, $totalActualizadas, $totalErrores]]
        );

        return $totalErrores > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolverSets(): array
    {
        if ($set = $this->option('set')) {
            return [trim($set)];
        }

        if ($sets = $this->option('sets')) {
            return array_filter(array_map('trim', explode(',', $sets)));
        }

        return self::SETS_POR_DEFECTO;
    }
}
