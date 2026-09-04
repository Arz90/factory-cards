<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Tareas programadas (Laravel 11 — routes/console.php)
|--------------------------------------------------------------------------
*/

// Sincronizar los sets más recientes de Pokémon TCG cada lunes a las 04:00
// para mantener los precios de compra actualizados con el mercado.
Schedule::command('singles:sync --sets=sv8pt5,sv8,sv7,sv6pt5,sv6')
    ->weeklyOn(1, '04:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/singles-sync.log'));
