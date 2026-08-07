<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios ──────────────────────────────────────────────────────
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

        // ── Franquicias ───────────────────────────────────────────────────
        $franchises = [
            ['name' => 'Pokémon',             'slug' => 'pokemon',    'color' => '#FFCB05', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Magic: The Gathering', 'slug' => 'mtg',        'color' => '#B5471B', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'One Piece TCG',        'slug' => 'one-piece',  'color' => '#EF4444', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Lorcana',              'slug' => 'lorcana',    'color' => '#8B5CF6', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Warhammer',            'slug' => 'warhammer',  'color' => '#374151', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Yu-Gi-Oh!',            'slug' => 'yugioh',     'color' => '#1D4ED8', 'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($franchises as $f) {
            Franchise::firstOrCreate(['slug' => $f['slug']], $f);
        }

        // ── Categorías ────────────────────────────────────────────────────
        $cats = [
            ['name' => 'Sobres y Boosters', 'slug' => 'sobres',      'sort_order' => 1, 'is_active' => true],
            ['name' => 'Displays',           'slug' => 'displays',    'sort_order' => 2, 'is_active' => true],
            ['name' => 'Mazos y Decks',      'slug' => 'mazos',       'sort_order' => 3, 'is_active' => true],
            ['name' => 'Colecciones',        'slug' => 'colecciones', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Accesorios',         'slug' => 'accesorios',  'sort_order' => 5, 'is_active' => true],
            ['name' => 'Sellados / Sealed',  'slug' => 'sellados',    'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($cats as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        // ── Productos de ejemplo ──────────────────────────────────────────
        $pokemonId  = Franchise::where('slug', 'pokemon')->value('id');
        $mtgId      = Franchise::where('slug', 'mtg')->value('id');
        $opId       = Franchise::where('slug', 'one-piece')->value('id');
        $sobresId   = Category::where('slug', 'sobres')->value('id');
        $displaysId = Category::where('slug', 'displays')->value('id');
        $mazosId    = Category::where('slug', 'mazos')->value('id');

        $products = [
            [
                'name'              => 'Sobre Pokémon Escarlata y Púrpura — Máscara del Crepúsculo',
                'slug'              => 'sobre-scarlet-violet-mask',
                'short_description' => '10 cartas por sobre. Posibilidad de carta holo y EX.',
                'price'             => 5.95,
                'original_price'    => 6.99,
                'stock'             => 200,
                'sku'               => 'PKM-SV06-BOO',
                'category_id'       => $sobresId,
                'franchise_id'      => $pokemonId,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'name'              => 'Display Pokémon Escarlata y Púrpura — 36 sobres',
                'slug'              => 'display-sv-mask',
                'short_description' => 'Caja sellada de 36 sobres Máscara del Crepúsculo.',
                'price'             => 189.95,
                'original_price'    => null,
                'stock'             => 15,
                'sku'               => 'PKM-SV06-DSP',
                'category_id'       => $displaysId,
                'franchise_id'      => $pokemonId,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'name'              => 'Pokémon 151 — Sobre individual',
                'slug'              => 'sobre-pokemon-151',
                'short_description' => 'Los Pokémon originales de Kanto en este set especial.',
                'price'             => 7.50,
                'original_price'    => null,
                'stock'             => 0,
                'sku'               => 'PKM-151-BOO',
                'category_id'       => $sobresId,
                'franchise_id'      => $pokemonId,
                'status'            => 'preorder',
                'is_featured'       => false,
            ],
            [
                'name'              => 'MTG Duskmourn — Sobre de Draft',
                'slug'              => 'mtg-duskmourn-draft',
                'short_description' => '15 cartas por sobre. La nueva expansión de terror.',
                'price'             => 4.50,
                'original_price'    => 5.00,
                'stock'             => 150,
                'sku'               => 'MTG-DSK-DFT',
                'category_id'       => $sobresId,
                'franchise_id'      => $mtgId,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'name'              => 'One Piece TCG — Mazo de inicio OP-09',
                'slug'              => 'op-starter-deck-op09',
                'short_description' => 'Mazo listo para jugar. Incluye 51 cartas.',
                'price'             => 14.95,
                'original_price'    => null,
                'stock'             => 30,
                'sku'               => 'OP-ST-09',
                'category_id'       => $mazosId,
                'franchise_id'      => $opId,
                'status'            => 'active',
                'is_featured'       => false,
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
