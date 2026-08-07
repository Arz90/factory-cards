<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Banner;
use App\Models\Event;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios ──────────────────────────────────────────────────────
        // Siempre primero: otros seeders pueden depender de que existan.
        User::firstOrCreate(['email' => 'admin@factorycards.com'], [
            'name'     => 'Admin Factory',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        User::firstOrCreate(['email' => 'cliente@factorycards.com'], [
            'name'     => 'Cliente Demo',
            'password' => Hash::make('demo123'),
            'role'     => 'customer',
        ]);

        // ── Catálogo (franquicias, categorías, productos) ─────────────────
        // Toda la lógica del catálogo vive en CatalogSeeder para mantener
        // este archivo limpio y fácil de extender.
        $this->call(CatalogSeeder::class);

        // ── Banners del hero slider ────────────────────────────────────────
        // Usamos placeholders de placehold.co para no depender de imágenes
        // reales en desarrollo. Se sobreescribirán al subir imágenes reales.
        $banners = [
            [
                'title'       => 'Próximos Lanzamientos TCG',
                'subtitle'    => 'PRECOMPRA YA DISPONIBLE',
                'image_path'  => 'images/banners/placeholder-1.jpg',
                'link_url'    => null,
                'button_text' => 'PRECOMPRA',
                'is_active'   => true,
                'order'       => 0,
            ],
            [
                'title'       => 'Magic: The Gathering',
                'subtitle'    => 'YA DISPONIBLE',
                'image_path'  => 'images/banners/placeholder-2.jpg',
                'link_url'    => null,
                'button_text' => 'VER PRODUCTOS',
                'is_active'   => true,
                'order'       => 1,
            ],
            [
                'title'       => 'Pokémon TCG',
                'subtitle'    => 'TEMPORADA 2025',
                'image_path'  => 'images/banners/placeholder-3.jpg',
                'link_url'    => null,
                'button_text' => 'EXPLORAR',
                'is_active'   => true,
                'order'       => 2,
            ],
        ];

        foreach ($banners as $b) {
            Banner::firstOrCreate(['title' => $b['title']], $b);
        }

        // ── Evento de prueba ──────────────────────────────────────────────
        // Presentación de MTG: El Hobbit — próximo fin de semana
        Event::firstOrCreate(
            ['title' => 'MAGIC: PRESENTACIÓN EL HOBBIT'],
            [
                'description'     => "Viernes 17h · Sábado 10h y 17h · Domingo 10h.\n\nInscripción: 34€. Incluye Pack de presentación de MTG: EL HOBBIT.\n\nEASY DRAFT: Juega 4 o más presentaciones y llévate LA PRIMERA JORNADA DE LA LIGA DE DRAFT de regalo.\n\nPREMIOS:\n- 1 Sobre por ronda ganada.\n- 1 Sobre a los resultados 0-3.",
                'start_date'      => now()->next('Friday')->setTime(17, 0, 0),
                'end_date'        => now()->next('Friday')->addDays(2)->setTime(20, 0, 0),
                'price'           => 34.00,
                'image_path'      => null,
                'google_maps_url' => 'https://maps.google.com',
                'is_active'       => true,
            ]
        );
    }
}
