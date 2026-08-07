<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Franchise;
use App\Models\Product;

/**
 * CatalogSeeder — Pobla el catálogo completo de Factory Cards.
 *
 * Estructura:
 *  1. Categorías principales (8 raíz)
 *  2. Subcategorías de Juegos TCG (sobres, displays, mazos, ETB)
 *  3. Subcategorías de Warhammer
 *  4. Franquicias (11)
 *  5. Productos (55+ realistas por franquicia/categoría)
 *  6. Invalidar caché del header (ViewServiceProvider)
 *
 * Seguro de re-ejecutar: usa updateOrCreate/firstOrCreate para no duplicar.
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════════════
        // 1. CATEGORÍAS RAÍZ (sin parent_id)
        // ══════════════════════════════════════════════════════════════════

        $categoriasRaiz = [
            ['name' => 'Juegos TCG',         'slug' => 'juegos-tcg',          'sort_order' => 1],
            ['name' => 'Juegos de Mesa',      'slug' => 'juegos-de-mesa',      'sort_order' => 2],
            ['name' => 'Warhammer',           'slug' => 'warhammer',           'sort_order' => 3],
            ['name' => 'Juegos de Rol',       'slug' => 'juegos-de-rol',       'sort_order' => 4],
            ['name' => 'LEGO',                'slug' => 'lego',                'sort_order' => 5],
            ['name' => 'Funko',               'slug' => 'funko',               'sort_order' => 6],
            ['name' => 'Accesorios',          'slug' => 'accesorios',          'sort_order' => 7],
            ['name' => 'Vende tus Cartas',    'slug' => 'vende-tus-cartas',    'sort_order' => 8],
        ];

        foreach ($categoriasRaiz as $c) {
            Category::updateOrCreate(
                ['slug' => $c['slug']],
                array_merge($c, ['parent_id' => null, 'is_active' => true])
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 2. SUBCATEGORÍAS DE JUEGOS TCG
        // ══════════════════════════════════════════════════════════════════

        $idTCG = Category::where('slug', 'juegos-tcg')->value('id');

        $subTCG = [
            ['name' => 'Sobres y Boosters',          'slug' => 'sobres',      'sort_order' => 1],
            ['name' => 'Displays (Cajas de sobres)',  'slug' => 'displays',    'sort_order' => 2],
            ['name' => 'Mazos de Inicio',             'slug' => 'mazos',       'sort_order' => 3],
            ['name' => 'Elite Trainer Box / Bundle',  'slug' => 'etb',         'sort_order' => 4],
            ['name' => 'Colecciones Selladas',        'slug' => 'colecciones', 'sort_order' => 5],
        ];

        foreach ($subTCG as $c) {
            Category::updateOrCreate(
                ['slug' => $c['slug']],
                array_merge($c, ['parent_id' => $idTCG, 'is_active' => true])
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 3. SUBCATEGORÍAS DE WARHAMMER
        // ══════════════════════════════════════════════════════════════════

        $idWar = Category::where('slug', 'warhammer')->value('id');

        $subWar = [
            ['name' => 'Combat Patrol',          'slug' => 'warhammer-combat-patrol', 'sort_order' => 1],
            ['name' => 'Sets de Inicio',         'slug' => 'warhammer-inicio',        'sort_order' => 2],
            ['name' => 'Pinturas y Herramientas','slug' => 'warhammer-pinturas',      'sort_order' => 3],
        ];

        foreach ($subWar as $c) {
            Category::updateOrCreate(
                ['slug' => $c['slug']],
                array_merge($c, ['parent_id' => $idWar, 'is_active' => true])
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 4. FRANQUICIAS
        // ══════════════════════════════════════════════════════════════════

        $franquicias = [
            ['name' => 'Magic: The Gathering', 'slug' => 'mtg',           'color' => '#B5471B', 'sort_order' =>  1],
            ['name' => 'Pokémon TCG',           'slug' => 'pokemon',       'color' => '#FFCB05', 'sort_order' =>  2],
            ['name' => 'One Piece TCG',         'slug' => 'one-piece',     'color' => '#EF4444', 'sort_order' =>  3],
            ['name' => 'Disney Lorcana',        'slug' => 'lorcana',       'color' => '#8B5CF6', 'sort_order' =>  4],
            ['name' => 'Star Wars Unlimited',   'slug' => 'star-wars',     'color' => '#0F172A', 'sort_order' =>  5],
            ['name' => 'Digimon TCG',           'slug' => 'digimon',       'color' => '#3B82F6', 'sort_order' =>  6],
            ['name' => 'Yu-Gi-Oh!',             'slug' => 'yugioh',        'color' => '#1D4ED8', 'sort_order' =>  7],
            ['name' => 'Altered TCG',           'slug' => 'altered',       'color' => '#10B981', 'sort_order' =>  8],
            ['name' => 'Riftbound',             'slug' => 'riftbound',     'color' => '#6366F1', 'sort_order' =>  9],
            ['name' => 'Warhammer 40,000',      'slug' => 'warhammer',     'color' => '#374151', 'sort_order' => 10],
            ['name' => 'Juegos de Mesa',        'slug' => 'juegos-de-mesa','color' => '#F59E0B', 'sort_order' => 11],
        ];

        foreach ($franquicias as $f) {
            Franchise::updateOrCreate(
                ['slug' => $f['slug']],
                array_merge($f, ['is_active' => true])
            );
        }

        // ══════════════════════════════════════════════════════════════════
        // 5. PRODUCTOS
        // Nomenclatura SKU: [FRANQUICIA]-[SET/SERIE]-[TIPO]
        // Tipos: BOO=Sobre, DSP=Display, STR=Starter, ETB=Elite Trainer Box,
        //        BDL=Bundle, CMD=Commander, ACC=Accesorio
        // ══════════════════════════════════════════════════════════════════

        // ── Recuperar IDs de categorías ──────────────────────────────────
        $catSobres     = Category::where('slug', 'sobres')->value('id');
        $catDisplays   = Category::where('slug', 'displays')->value('id');
        $catMazos      = Category::where('slug', 'mazos')->value('id');
        $catEtb        = Category::where('slug', 'etb')->value('id');
        $catWar        = Category::where('slug', 'warhammer')->value('id');
        $catWarCP      = Category::where('slug', 'warhammer-combat-patrol')->value('id');
        $catWarPint    = Category::where('slug', 'warhammer-pinturas')->value('id');
        $catMesa       = Category::where('slug', 'juegos-de-mesa')->value('id');
        $catRol        = Category::where('slug', 'juegos-de-rol')->value('id');
        $catLego       = Category::where('slug', 'lego')->value('id');
        $catFunko      = Category::where('slug', 'funko')->value('id');
        $catAccesorios = Category::where('slug', 'accesorios')->value('id');

        // ── Recuperar IDs de franquicias ─────────────────────────────────
        $fPokemon   = Franchise::where('slug', 'pokemon')->value('id');
        $fMtg       = Franchise::where('slug', 'mtg')->value('id');
        $fOp        = Franchise::where('slug', 'one-piece')->value('id');
        $fLorcana   = Franchise::where('slug', 'lorcana')->value('id');
        $fStarWars  = Franchise::where('slug', 'star-wars')->value('id');
        $fDigimon   = Franchise::where('slug', 'digimon')->value('id');
        $fYugioh    = Franchise::where('slug', 'yugioh')->value('id');
        $fAltered   = Franchise::where('slug', 'altered')->value('id');
        $fRiftbound = Franchise::where('slug', 'riftbound')->value('id');
        $fWarhammer = Franchise::where('slug', 'warhammer')->value('id');
        $fMesa      = Franchise::where('slug', 'juegos-de-mesa')->value('id');

        // ── Lista completa de productos ───────────────────────────────────
        $productos = [

            // ─────────────────────────────────────────────────────────────
            // POKÉMON TCG
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'PKM-SV08-BOO',
                'name'              => 'Sobre Pokémon SV08 — Puntos Temporales',
                'slug'              => 'sobre-pokemon-sv08-puntos-temporales',
                'short_description' => '10 cartas por sobre. Posibilidad de carta EX, holo y ultra rara.',
                'description'       => 'La octava expansión de la serie Escarlata y Púrpura llega con más de 190 cartas nuevas. Cada sobre contiene 10 cartas con posibilidad de obtener cartas EX, cartas ilustradas especiales y cartas raras doradas.',
                'price'             => 5.95,
                'original_price'    => 6.99,
                'stock'             => 200,
                'category_id'       => $catSobres,
                'franchise_id'      => $fPokemon,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'PKM-SV08-DSP',
                'name'              => 'Display Pokémon SV08 — 36 Sobres Puntos Temporales',
                'slug'              => 'display-pokemon-sv08-puntos-temporales',
                'short_description' => 'Caja sellada de 36 sobres de la expansión Puntos Temporales.',
                'description'       => 'La opción perfecta para coleccionistas y jugadores competitivos. Caja sellada de fábrica con 36 sobres del nuevo set Puntos Temporales de Pokémon Escarlata y Púrpura.',
                'price'             => 189.95,
                'original_price'    => null,
                'stock'             => 12,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fPokemon,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'PKM-SV08-ETB',
                'name'              => 'Elite Trainer Box Pokémon — Puntos Temporales',
                'slug'              => 'etb-pokemon-sv08-puntos-temporales',
                'short_description' => '9 sobres + cartas de energía holo + accesorios premium.',
                'description'       => 'Incluye 9 sobres Puntos Temporales, 65 fundas protectoras ilustradas, 45 cartas de energía foil, dados, marcadores de condición y una caja organizadora de calidad.',
                'price'             => 54.95,
                'original_price'    => 59.95,
                'stock'             => 25,
                'category_id'       => $catEtb,
                'franchise_id'      => $fPokemon,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'PKM-SV-STR-CHI',
                'name'              => 'Mazo de Inicio Pokémon — Chien-Pao ex',
                'slug'              => 'mazo-inicio-pokemon-chien-pao-ex',
                'short_description' => 'Mazo de 60 cartas listo para jugar. Incluye ficha de Chien-Pao ex.',
                'description'       => 'Comienza a jugar de inmediato con este mazo de inicio. Contiene 60 cartas preseleccionadas, guía rápida, moneda de juego, marcadores de daño y una ficha especial de Chien-Pao ex.',
                'price'             => 14.95,
                'original_price'    => null,
                'stock'             => 30,
                'category_id'       => $catMazos,
                'franchise_id'      => $fPokemon,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'PKM-151-BOO',
                'name'              => 'Sobre Pokémon 151 — Edición Especial Kanto',
                'slug'              => 'sobre-pokemon-151-kanto',
                'short_description' => 'Los 151 Pokémon originales de Kanto con diseños únicos y retro.',
                'description'       => 'Celebra la primera generación con este set especial que recupera los 151 Pokémon originales con ilustraciones reimaginadas. Alta demanda — disponible en precompra.',
                'price'             => 7.50,
                'original_price'    => null,
                'stock'             => 0,
                'category_id'       => $catSobres,
                'franchise_id'      => $fPokemon,
                'status'            => 'preorder',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // MAGIC: THE GATHERING
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'MTG-FDN-DFT',
                'name'              => 'Sobre de Draft MTG — Fundamentos (FDN)',
                'slug'              => 'sobre-mtg-fundamentos-draft',
                'short_description' => '15 cartas por sobre. La expansión de entrada perfecta para nuevos jugadores.',
                'description'       => 'Fundamentos de Magic reúne las cartas más icónicas e imprescindibles de la historia del juego en un único set diseñado para presentar Magic a nuevos jugadores y para el draft en equipo.',
                'price'             => 4.50,
                'original_price'    => 5.00,
                'stock'             => 300,
                'category_id'       => $catSobres,
                'franchise_id'      => $fMtg,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'MTG-BLB-SBB',
                'name'              => 'Set Booster Box MTG Bloomburrow — 30 Sobres',
                'slug'              => 'set-booster-box-mtg-bloomburrow',
                'short_description' => 'Caja de 30 Set Boosters de la expansión Bloomburrow.',
                'description'       => 'Bloomburrow te lleva a un plano habitado únicamente por animales con habilidades mágicas. Cada Set Booster incluye al menos 1 carta de rareza rara o mítica. La caja contiene 30 sobres sellados de fábrica.',
                'price'             => 149.95,
                'original_price'    => null,
                'stock'             => 8,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fMtg,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'MTG-DSK-CMD',
                'name'              => 'Commander Deck MTG Duskmourn — Horrores de la Casa',
                'slug'              => 'commander-deck-mtg-duskmourn-horrores',
                'short_description' => 'Mazo Commander de 100 cartas listo para jugar con 10 cartas nuevas exclusivas.',
                'description'       => 'Sumérgete en el terror de Duskmourn con este Commander Deck lleno de horrores y suspenso. Incluye 100 cartas (10 exclusivas del formato), guía de estrategia y ficha de Commander.',
                'price'             => 44.95,
                'original_price'    => 49.95,
                'stock'             => 15,
                'category_id'       => $catMazos,
                'franchise_id'      => $fMtg,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'MTG-FDN-BDL',
                'name'              => 'Bundle MTG Fundamentos',
                'slug'              => 'bundle-mtg-fundamentos',
                'short_description' => '9 sobres + 40 tierras foil + dados especiales y extras.',
                'description'       => 'El Bundle de Fundamentos incluye 9 Play Boosters, 40 tierras básicas foil, 1 sobre alternativo, 1 sobre de promo pack, dados especiales de veinte caras y la caja organizadora oficial.',
                'price'             => 44.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catEtb,
                'franchise_id'      => $fMtg,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'MTG-MH3-DSP',
                'name'              => 'Display Draft Booster MTG Modern Horizons 3 — 36 Sobres',
                'slug'              => 'display-mtg-modern-horizons-3',
                'short_description' => '36 sobres de la expansión más poderosa del formato Modern.',
                'description'       => 'Modern Horizons 3 introduce cartas directamente al formato Modern sin pasar por Standard. Una de las expansiones más esperadas y poderosas. Caja de 36 Draft Boosters sellada.',
                'price'             => 299.95,
                'original_price'    => null,
                'stock'             => 5,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fMtg,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // ONE PIECE TCG
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'OP-OP10-BOO',
                'name'              => 'Sobre One Piece TCG — OP-10 Royal Blood',
                'slug'              => 'sobre-one-piece-op10-royal-blood',
                'short_description' => '12 cartas por sobre. El set más reciente de One Piece TCG.',
                'description'       => 'Royal Blood es el décimo set de One Piece Card Game y presenta a los personajes más icónicos de los Siete Guerreros del Mar con nuevas mecánicas de juego. 12 cartas por sobre con posibilidad de cartas alternativas.',
                'price'             => 4.50,
                'original_price'    => null,
                'stock'             => 250,
                'category_id'       => $catSobres,
                'franchise_id'      => $fOp,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'OP-OP10-DSP',
                'name'              => 'Display One Piece TCG OP-10 — 24 Sobres Royal Blood',
                'slug'              => 'display-one-piece-op10-royal-blood',
                'short_description' => 'Caja sellada de 24 sobres Royal Blood.',
                'description'       => 'Consigue el máximo rendimiento con la caja completa de Royal Blood. 24 sobres sellados de fábrica con alta probabilidad de obtener cartas SECRET RARE y ALTERNATE ART.',
                'price'             => 99.95,
                'original_price'    => null,
                'stock'             => 10,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fOp,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'OP-ST21',
                'name'              => 'Mazo de Inicio One Piece TCG — ST-21 Koby',
                'slug'              => 'mazo-inicio-one-piece-st21-koby',
                'short_description' => 'Mazo de 51 cartas + 10 TOKEN con Koby como líder.',
                'description'       => 'El mazo ST-21 presenta a Koby como líder e incluye 51 cartas preseleccionadas más 10 cartas TOKEN. Perfecto para comenzar a jugar o ampliar tus opciones de liderazgo.',
                'price'             => 14.95,
                'original_price'    => null,
                'stock'             => 35,
                'category_id'       => $catMazos,
                'franchise_id'      => $fOp,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'OP-PRM-BST',
                'name'              => 'Premium Booster One Piece — THE BEST',
                'slug'              => 'premium-booster-one-piece-the-best',
                'short_description' => 'Sobre premium con las mejores cartas reimprimidas en versión mejorada.',
                'description'       => 'THE BEST Premium Booster reúne las cartas más populares y poderosas de los primeros sets, reimpresas con nuevo arte y acabados premium. Incluye 8 cartas por sobre con garantía de al menos 1 PARALLEL RARE.',
                'price'             => 5.95,
                'original_price'    => null,
                'stock'             => 80,
                'category_id'       => $catSobres,
                'franchise_id'      => $fOp,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // DISNEY LORCANA
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'LOR-CH7-BOO',
                'name'              => 'Sobre Lorcana — Azurite Sea (Capítulo 7)',
                'slug'              => 'sobre-lorcana-azurite-sea-capitulo-7',
                'short_description' => '12 cartas por sobre. El mundo submarino de Disney en formato TCG.',
                'description'       => 'El séptimo capítulo de Disney Lorcana nos lleva a las profundidades del Mar de Azurita, con personajes de La Sirenita, Finding Nemo y más películas de mundo acuático.',
                'price'             => 5.50,
                'original_price'    => null,
                'stock'             => 150,
                'category_id'       => $catSobres,
                'franchise_id'      => $fLorcana,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'LOR-CH7-DSP',
                'name'              => 'Display Lorcana Capítulo 7 — 24 Sobres Azurite Sea',
                'slug'              => 'display-lorcana-capitulo-7-azurite-sea',
                'short_description' => 'Caja sellada de 24 sobres del Capítulo 7 de Lorcana.',
                'description'       => 'La caja completa de 24 sobres es la mejor forma de construir tu colección de Azurite Sea. Mayor probabilidad de obtener cartas ENCHANTED (las más escasas y valiosas de Lorcana).',
                'price'             => 119.95,
                'original_price'    => 129.95,
                'stock'             => 8,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fLorcana,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'LOR-CH7-STR',
                'name'              => 'Starter Deck Lorcana — Ámbar / Esmeralda',
                'slug'              => 'starter-deck-lorcana-ambar-esmeralda',
                'short_description' => 'Mazo de inicio de 60 cartas con personajes Ámbar y Esmeralda.',
                'description'       => 'Empieza tu aventura en Lorcana con este mazo de inicio que combina los colores Ámbar y Esmeralda. Incluye 60 cartas, tablero de campo, dados y guía rápida del juego.',
                'price'             => 15.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catMazos,
                'franchise_id'      => $fLorcana,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'LOR-CH7-TRV',
                'name'              => "Illumineer's Trove Lorcana — Azurite Sea",
                'slug'              => 'illumineers-trove-lorcana-azurite-sea',
                'short_description' => 'Caja premium con 8 sobres + 1 sobre exclusivo + extras de colección.',
                'description'       => "El Illumineer's Trove es la experiencia premium de Lorcana: 8 sobres del Capítulo 7, 1 sobre exclusivo de la Trove, tablero de juego ilustrado, bolsa de tela y una carta promo exclusiva.",
                'price'             => 49.95,
                'original_price'    => null,
                'stock'             => 12,
                'category_id'       => $catEtb,
                'franchise_id'      => $fLorcana,
                'status'            => 'active',
                'is_featured'       => true,
            ],

            // ─────────────────────────────────────────────────────────────
            // STAR WARS UNLIMITED
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'SWU-TOR-BOO',
                'name'              => 'Sobre Star Wars Unlimited — Twilight of the Republic',
                'slug'              => 'sobre-star-wars-unlimited-twilight-republic',
                'short_description' => '16 cartas por sobre. Las Guerras Clon llegan a Star Wars Unlimited.',
                'description'       => 'Twilight of the Republic es el tercer set de Star Wars: Unlimited y se centra en la era de las Guerras Clon con personajes como Anakin Skywalker, Ahsoka Tano y el Conde Dooku.',
                'price'             => 4.95,
                'original_price'    => null,
                'stock'             => 200,
                'category_id'       => $catSobres,
                'franchise_id'      => $fStarWars,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'SWU-TOR-DSP',
                'name'              => 'Display Star Wars Unlimited — 24 Sobres Twilight of the Republic',
                'slug'              => 'display-star-wars-unlimited-twilight-republic',
                'short_description' => 'Caja sellada de 24 sobres de Twilight of the Republic.',
                'description'       => 'La caja completa de 24 sobres de Twilight of the Republic con posibilidad de obtener cartas HYPERSPACE y SHOWCASE en acabados alternativos exclusivos.',
                'price'             => 109.95,
                'original_price'    => null,
                'stock'             => 6,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fStarWars,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'SWU-TOR-2PK',
                'name'              => 'Two-Player Starter Set Star Wars Unlimited — Twilight of Republic',
                'slug'              => 'starter-set-dos-jugadores-star-wars-unlimited',
                'short_description' => '2 mazos de 50 cartas listos para jugar + 2 sobres de refuerzo.',
                'description'       => 'El set perfecto para empezar en Star Wars: Unlimited con un amigo. Incluye dos mazos preconstructidos, 2 sobres de refuerzo, tablero de juego, dados y marcadores.',
                'price'             => 19.95,
                'original_price'    => null,
                'stock'             => 30,
                'category_id'       => $catMazos,
                'franchise_id'      => $fStarWars,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'SWU-HSP-BOO',
                'name'              => 'Hyperspace Booster — Star Wars Unlimited',
                'slug'              => 'hyperspace-booster-star-wars-unlimited',
                'short_description' => 'Sobre especial con 1 carta HYPERSPACE garantizada + 2 cartas más.',
                'description'       => 'El Hyperspace Booster de Star Wars: Unlimited garantiza 1 carta en acabado Hyperspace (variante alternativa y brillante) junto a 2 cartas adicionales del set actual.',
                'price'             => 1.95,
                'original_price'    => null,
                'stock'             => 150,
                'category_id'       => $catSobres,
                'franchise_id'      => $fStarWars,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // DIGIMON TCG
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'DGM-BT19-BOO',
                'name'              => 'Sobre Digimon TCG — BT-19 Exceeding Light',
                'slug'              => 'sobre-digimon-bt19-exceeding-light',
                'short_description' => '12 cartas por sobre. El set más brillante de Digimon TCG.',
                'description'       => 'Exceeding Light es el decimonoveno booster set de Digimon Card Game. Presenta nuevas versiones de Digimon legendarios con el nivel EXCEED, una nueva mecánica que potencia tus Digimon más allá de sus límites.',
                'price'             => 4.25,
                'original_price'    => null,
                'stock'             => 120,
                'category_id'       => $catSobres,
                'franchise_id'      => $fDigimon,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'DGM-BT19-DSP',
                'name'              => 'Display Digimon BT-19 — 24 Sobres Exceeding Light',
                'slug'              => 'display-digimon-bt19-exceeding-light',
                'short_description' => 'Caja sellada de 24 sobres de Exceeding Light.',
                'description'       => 'La caja completa de Exceeding Light para coleccionistas y jugadores. 24 sobres con posibilidad de cartas SECRET RARE y ALTERNATE ART de los Digimon más poderosos.',
                'price'             => 89.95,
                'original_price'    => null,
                'stock'             => 7,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fDigimon,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'DGM-ST19',
                'name'              => 'Starter Deck Digimon — ST-19 Shinegreymon',
                'slug'              => 'starter-deck-digimon-st19-shinegreymon',
                'short_description' => 'Mazo de inicio de 54 cartas con Shinegreymon como líder.',
                'description'       => 'El deck ST-19 tiene a Shinegreymon como protagonista con una estrategia de evolución acelerada. 54 cartas preconstructidas, con alta sinergía para jugadores intermedios.',
                'price'             => 14.95,
                'original_price'    => null,
                'stock'             => 25,
                'category_id'       => $catMazos,
                'franchise_id'      => $fDigimon,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'DGM-BT20-DSP',
                'name'              => 'Display Digimon BT-20 — 24 Sobres (PRECOMPRA)',
                'slug'              => 'display-digimon-bt20-precompra',
                'short_description' => 'Reserva ya tu caja del próximo gran set de Digimon TCG.',
                'description'       => 'El vigésimo booster set de Digimon Card Game ya está disponible en precompra. Asegura tu caja antes de que se agoten. Se enviará el día de lanzamiento oficial.',
                'price'             => 89.95,
                'original_price'    => null,
                'stock'             => 0,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fDigimon,
                'status'            => 'preorder',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // YU-GI-OH!
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'YGO-PHN-BOO',
                'name'              => 'Sobre Yu-Gi-Oh! — Phantom Nightmare',
                'slug'              => 'sobre-yugioh-phantom-nightmare',
                'short_description' => '9 cartas por sobre. Nuevos tipos de monstruos y estrategias.',
                'description'       => 'Phantom Nightmare es la quinta expansión de la era VRAINS con más de 100 cartas nuevas, incluyendo 10 Ultra Rares y 8 Secret Rares. Introduce nuevos arquetipos como los Voiceless Voice.',
                'price'             => 4.25,
                'original_price'    => null,
                'stock'             => 180,
                'category_id'       => $catSobres,
                'franchise_id'      => $fYugioh,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'YGO-PHN-DSP',
                'name'              => 'Display Yu-Gi-Oh! Phantom Nightmare — 24 Sobres',
                'slug'              => 'display-yugioh-phantom-nightmare',
                'short_description' => 'Caja de 24 sobres Phantom Nightmare sellada de fábrica.',
                'description'       => 'La caja de 24 sobres de Phantom Nightmare es la mejor opción para completar el set. Mayor probabilidad estadística de obtener todas las Secret Rares del set.',
                'price'             => 89.95,
                'original_price'    => null,
                'stock'             => 9,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fYugioh,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'YGO-SDC-FRK',
                'name'              => 'Structure Deck Yu-Gi-Oh! — Fire Kings',
                'slug'              => 'structure-deck-yugioh-fire-kings',
                'short_description' => 'Mazo de estructura de 42 cartas listo para el juego competitivo.',
                'description'       => 'El Structure Deck Fire Kings reactiva uno de los arquetipos más populares del juego con cartas actualizadas y nuevas combinaciones. Incluye 42 cartas (2 Ultra Rares, 3 Super Rares), tablero de juego y guía.',
                'price'             => 12.95,
                'original_price'    => null,
                'stock'             => 30,
                'category_id'       => $catMazos,
                'franchise_id'      => $fYugioh,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'YGO-LC-25A',
                'name'              => 'Legendary Collection Yu-Gi-Oh! — 25º Aniversario',
                'slug'              => 'legendary-collection-yugioh-25-aniversario',
                'short_description' => 'Colección especial de 5 sobres + cartas exclusivas del 25 aniversario.',
                'description'       => 'Celebra 25 años de Yu-Gi-Oh! con esta colección especial que incluye 5 sobres Mega-Tin, cartas en versión "Quarter Century Secret Rare" y reproducciones de cartas históricas del juego.',
                'price'             => 34.95,
                'original_price'    => 39.95,
                'stock'             => 15,
                'category_id'       => $catEtb,
                'franchise_id'      => $fYugioh,
                'status'            => 'active',
                'is_featured'       => true,
            ],

            // ─────────────────────────────────────────────────────────────
            // ALTERED TCG
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'ALT-BTG-BOO',
                'name'              => 'Sobre Altered TCG — Beyond the Gates (BTG)',
                'slug'              => 'sobre-altered-tcg-beyond-the-gates',
                'short_description' => '10 cartas por sobre. El TCG con arte generativo único.',
                'description'       => 'Altered es el primer TCG donde cada carta que abres puede tener arte único generado por IA. Beyond the Gates es la segunda expansión con más de 200 nuevas cartas y mecánicas. Nunca tendrás una carta igual que la de otro jugador.',
                'price'             => 5.95,
                'original_price'    => null,
                'stock'             => 100,
                'category_id'       => $catSobres,
                'franchise_id'      => $fAltered,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'ALT-BTG-STR',
                'name'              => 'Starter Set Altered TCG — Beyond the Gates',
                'slug'              => 'starter-set-altered-tcg-beyond-the-gates',
                'short_description' => '2 mazos de inicio + 2 sobres + tutorial completo.',
                'description'       => 'El mejor punto de entrada para Altered: incluye 2 mazos de inicio con arte único, 2 sobres Beyond the Gates para reforzar tu colección, guía de juego completa y acceso a la aplicación oficial.',
                'price'             => 24.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catMazos,
                'franchise_id'      => $fAltered,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'ALT-BTG-DSP',
                'name'              => 'Display Altered TCG — 24 Sobres Beyond the Gates',
                'slug'              => 'display-altered-tcg-beyond-the-gates',
                'short_description' => 'Caja de 24 sobres de la segunda expansión de Altered.',
                'description'       => 'La caja completa de 24 sobres de Beyond the Gates con arte generativo. Cada caja garantiza una variedad única de illustraciones. Incluye la posibilidad de obtener cartas UNIQUE (solo una en el mundo).',
                'price'             => 134.95,
                'original_price'    => null,
                'stock'             => 5,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fAltered,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // RIFTBOUND
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'RFT-LCH-STR',
                'name'              => 'Starter Pack Riftbound — Edición de Lanzamiento',
                'slug'              => 'starter-pack-riftbound-edicion-lanzamiento',
                'short_description' => 'Sé el primero en explorar Riftbound — precompra ya disponible.',
                'description'       => 'Riftbound es el nuevo TCG de Riot Games basado en el universo de League of Legends y Valorant. El Starter Pack de lanzamiento incluye mazo preconstructido, sobre especial y extras exclusivos de Day One. Disponible en precompra.',
                'price'             => 19.95,
                'original_price'    => null,
                'stock'             => 0,
                'category_id'       => $catMazos,
                'franchise_id'      => $fRiftbound,
                'status'            => 'preorder',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'RFT-LCH-DSP',
                'name'              => 'Display Riftbound — Edición de Lanzamiento (24 sobres)',
                'slug'              => 'display-riftbound-edicion-lanzamiento',
                'short_description' => 'Caja sellada de 24 sobres del lanzamiento de Riftbound.',
                'description'       => 'Asegura tu caja completa del lanzamiento de Riftbound con 24 sobres. Los primeros compradores recibirán la caja en el día de lanzamiento oficial con envío prioritario.',
                'price'             => 89.95,
                'original_price'    => null,
                'stock'             => 0,
                'category_id'       => $catDisplays,
                'franchise_id'      => $fRiftbound,
                'status'            => 'preorder',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // WARHAMMER 40,000
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'WHM-CP-SMA',
                'name'              => 'Combat Patrol Warhammer 40K — Space Marines',
                'slug'              => 'combat-patrol-warhammer-space-marines',
                'short_description' => 'Ejército de inicio Space Marines (25+ miniaturas) listo para pintar y jugar.',
                'description'       => 'El Combat Patrol de Space Marines es la forma más económica de empezar en Warhammer 40,000. Incluye más de 25 miniaturas multicomponente: Capitán, Intercessors, Impulsors y más, suficientes para partidas de Combat Patrol.',
                'price'             => 119.00,
                'original_price'    => 130.00,
                'stock'             => 5,
                'category_id'       => $catWarCP,
                'franchise_id'      => $fWarhammer,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'WHM-CP-NCR',
                'name'              => 'Combat Patrol Warhammer 40K — Necrons',
                'slug'              => 'combat-patrol-warhammer-necrons',
                'short_description' => 'Ejército de inicio Necrons (20+ miniaturas) listo para pintar y jugar.',
                'description'       => 'Los Necrons despiertan de su letargo eterno. Este Combat Patrol incluye más de 20 miniaturas: Overlord, Immortals, Scarabs y más. Una fuerza equilibrada perfecta para el formato Combat Patrol de 500 puntos.',
                'price'             => 119.00,
                'original_price'    => null,
                'stock'             => 4,
                'category_id'       => $catWarCP,
                'franchise_id'      => $fWarhammer,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'WHM-LEV-STR',
                'name'              => 'Warhammer 40K — Set de Inicio Leviathan',
                'slug'              => 'warhammer-40k-set-inicio-leviathan',
                'short_description' => 'La caja de dos ejércitos más completa para empezar en 40K.',
                'description'       => 'Leviathan es el set de inicio definitivo de la 10ª Edición de Warhammer 40,000. Incluye más de 70 miniaturas entre Space Marines y Tyranids, el libro de reglas completo de la 10ª edición y dados y plantillas.',
                'price'             => 169.95,
                'original_price'    => null,
                'stock'             => 0,
                'category_id'       => $catWar,
                'franchise_id'      => $fWarhammer,
                'status'            => 'preorder',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'WHM-CIT-BST',
                'name'              => 'Set de Pinturas Citadel — Base + Sombra Esenciales',
                'slug'              => 'set-pinturas-citadel-base-sombra-esenciales',
                'short_description' => '13 botes de pintura Citadel Base y Shade para empezar a pintar.',
                'description'       => 'El set esencial de pinturas Citadel para principiantes: incluye 8 colores Base (los más usados de Warhammer) y 5 colores Shade para dar profundidad y sombras sin esfuerzo. Compatible con todas las miniaturas Citadel.',
                'price'             => 34.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catWarPint,
                'franchise_id'      => $fWarhammer,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'WHM-CIT-BRS',
                'name'              => 'Pincel Citadel — Detalle Medium (Paquete de 3)',
                'slug'              => 'pincel-citadel-detalle-medium-pack-3',
                'short_description' => 'Pack de 3 pinceles Citadel Detalle Medium para pintado de miniaturas.',
                'description'       => 'Los pinceles Citadel están diseñados específicamente para pintar miniaturas. El tamaño Detalle Medium es el más versátil: ideal para capas, sombreados y detalles de tamaño medio.',
                'price'             => 8.95,
                'original_price'    => null,
                'stock'             => 35,
                'category_id'       => $catWarPint,
                'franchise_id'      => $fWarhammer,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // JUEGOS DE MESA
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'JDMSA-CTN-BSE',
                'name'              => 'Catan — Juego Base (Edición Española)',
                'slug'              => 'catan-juego-base-edicion-espanola',
                'short_description' => 'El clásico juego de construcción y estrategia. 3-4 jugadores.',
                'description'       => 'Catan es el juego de mesa estratégico más premiado del mundo. Construye asentamientos, comercia recursos y domina la isla de Catán antes que tus rivales. Para 3-4 jugadores, a partir de 10 años.',
                'price'             => 44.95,
                'original_price'    => null,
                'stock'             => 15,
                'category_id'       => $catMesa,
                'franchise_id'      => $fMesa,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'JDMSA-TTR-EUR',
                'name'              => 'Ticket to Ride Europa',
                'slug'              => 'ticket-to-ride-europa',
                'short_description' => 'Conecta ciudades europeas con tus trenes. 2-5 jugadores.',
                'description'       => 'Ticket to Ride Europa añade túneles, ferrys y estaciones al ya famoso sistema de juego. Conecta ciudades de toda Europa antes que tus rivales. Para 2-5 jugadores, a partir de 8 años.',
                'price'             => 44.95,
                'original_price'    => null,
                'stock'             => 10,
                'category_id'       => $catMesa,
                'franchise_id'      => $fMesa,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'JDMSA-PAN-BSE',
                'name'              => 'Pandemic — Edición Española',
                'slug'              => 'pandemic-edicion-espanola',
                'short_description' => 'Juego cooperativo: salva al mundo de cuatro enfermedades. 2-4 jugadores.',
                'description'       => 'En Pandemic los jugadores cooperan para contener y erradicar cuatro enfermedades que amenazan a la humanidad. Un juego cooperativo de alta tensión donde la comunicación y estrategia son clave. Para 2-4 jugadores.',
                'price'             => 34.95,
                'original_price'    => 39.95,
                'stock'             => 12,
                'category_id'       => $catMesa,
                'franchise_id'      => $fMesa,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'JDMSA-AKH-3ED',
                'name'              => 'Arkham Horror (3ª Edición)',
                'slug'              => 'arkham-horror-tercera-edicion',
                'short_description' => 'Cooperativo de terror lovecraftiano. 1-6 investigadores.',
                'description'       => 'La tercera edición de Arkham Horror ofrece la experiencia más completa del horror cósmico en formato juego de mesa. 1 a 6 jugadores colaboran como investigadores para sellar los portales antes del despertar de los Primigenios.',
                'price'             => 69.95,
                'original_price'    => null,
                'stock'             => 6,
                'category_id'       => $catMesa,
                'franchise_id'      => $fMesa,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // JUEGOS DE ROL
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'JDR-DND-PHB',
                'name'              => 'Dungeons & Dragons — Manual del Jugador (2024)',
                'slug'              => 'dungeons-dragons-manual-jugador-2024',
                'short_description' => 'El libro base de D&D 5ª edición revisada. Imprescindible.',
                'description'       => 'El Manual del Jugador de 2024 es la revisión definitiva de Dungeons & Dragons 5ª Edición. Incluye todas las clases, razas, hechizos y reglas actualizadas. El libro esencial para todo jugador de D&D.',
                'price'             => 44.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catRol,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'JDR-DND-STR',
                'name'              => "Dungeons & Dragons — Starter Set: Las Minas del Dragón",
                'slug'              => 'dungeons-dragons-starter-set-minas-dragon',
                'short_description' => 'Aventura completa para 2-6 jugadores. No se necesitan dados propios.',
                'description'       => 'El Starter Set de D&D es la forma perfecta de empezar en los juegos de rol. Incluye el libro de reglas simplificado, la aventura Las Minas del Dragón, 5 personajes pregenerados, dados y un sobre de cartas de hechizo.',
                'price'             => 19.95,
                'original_price'    => null,
                'stock'             => 30,
                'category_id'       => $catRol,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'JDR-PF2-CRB',
                'name'              => 'Pathfinder — Libro Básico del Jugador (2ª Edición)',
                'slug'              => 'pathfinder-libro-basico-jugador-segunda-edicion',
                'short_description' => 'El RPG más complejo y personalizable del mercado.',
                'description'       => 'Pathfinder 2ª Edición ofrece un sistema de juego profundo y altamente personalizable. El Libro Básico incluye todas las reglas, 6 clases base, 6 ascendencias, cientos de habilidades y el sistema de las tres acciones.',
                'price'             => 59.95,
                'original_price'    => null,
                'stock'             => 8,
                'category_id'       => $catRol,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'JDR-DND-ADV',
                'name'              => 'D&D — El Resurgir de los Dragones (Aventura)',
                'slug'              => 'dd-el-resurgir-de-los-dragones-aventura',
                'short_description' => 'Aventura oficial de D&D para niveles 1-12. 4-6 jugadores.',
                'description'       => 'Una aventura épica que lleva a los héroes a través de múltiples regiones del mundo en busca de los artefactos que pueden detener el regreso del Rey Dragón. Compatible con D&D 5ª Edición (versiones 2014 y 2024).',
                'price'             => 34.95,
                'original_price'    => null,
                'stock'             => 12,
                'category_id'       => $catRol,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // LEGO
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'LEGO-SW-MF75',
                'name'              => 'LEGO Star Wars — Millennium Falcon (75375)',
                'slug'              => 'lego-star-wars-millennium-falcon-75375',
                'short_description' => '1.351 piezas. El set más icónico de LEGO Star Wars.',
                'description'       => 'Construye el Halcón Milenario más icónico de la historia de LEGO. Este set de 1.351 piezas incluye las minifiguras de Han Solo, Chewbacca, Leia, Luke Skywalker, Obi-Wan Kenobi y Darth Vader. El regalo definitivo para fans de Star Wars.',
                'price'             => 849.95,
                'original_price'    => null,
                'stock'             => 2,
                'category_id'       => $catLego,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'LEGO-PKM-PIK',
                'name'              => 'LEGO — Pikachu (31257)',
                'slug'              => 'lego-pokemon-pikachu-31257',
                'short_description' => '1.095 piezas. Figura de Pikachu a tamaño real para coleccionar.',
                'description'       => 'Construye a Pikachu a escala de escritorio con este set de 1.095 piezas inspirado en el Pokémon más famoso del mundo. Incluye detalles icónicos como las mejillas rojas, la cola en punta y las orejas negras.',
                'price'             => 74.95,
                'original_price'    => null,
                'stock'             => 10,
                'category_id'       => $catLego,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'LEGO-HP-HOG',
                'name'              => 'LEGO Harry Potter — Castillo de Hogwarts (76419)',
                'slug'              => 'lego-harry-potter-castillo-hogwarts-76419',
                'short_description' => '2.660 piezas. La representación más completa de Hogwarts en LEGO.',
                'description'       => 'El Castillo de Hogwarts de LEGO Harry Potter con 2.660 piezas recrea los rincones más mágicos del castillo: el Gran Salón, la Torre de Astronomía, la Sala de las Profecías y más. Incluye 11 minifiguras y 2 figuras de dragón.',
                'price'             => 469.95,
                'original_price'    => null,
                'stock'             => 3,
                'category_id'       => $catLego,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // FUNKO POP
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'FNK-PKM-PIK',
                'name'              => 'Funko Pop Pokémon — Pikachu (#353)',
                'slug'              => 'funko-pop-pokemon-pikachu-353',
                'short_description' => 'Figura Funko Pop de Pikachu. Talla estándar (~10 cm).',
                'description'       => 'El Funko Pop de Pikachu es uno de los más populares de toda la línea Pokémon. Figura de vinilo de alta calidad en talla estándar (~10 cm) con su inconfundible diseño de cabeza grande. Caja ventana oficial.',
                'price'             => 15.95,
                'original_price'    => null,
                'stock'             => 40,
                'category_id'       => $catFunko,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'FNK-OP-LUF',
                'name'              => 'Funko Pop One Piece — Monkey D. Luffy (#98)',
                'slug'              => 'funko-pop-one-piece-monkey-d-luffy-98',
                'short_description' => 'Figura Funko Pop del Capitán Luffy con su sombrero de paja.',
                'description'       => 'El Funko Pop de Monkey D. Luffy captura perfectamente al capitán del Sombrero de Paja con su icónica sonrisa y sombrero. Ideal para fans del manga y el anime de One Piece.',
                'price'             => 15.95,
                'original_price'    => null,
                'stock'             => 35,
                'category_id'       => $catFunko,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'FNK-HP-HAR',
                'name'              => 'Funko Pop Harry Potter — Harry Potter (#01)',
                'slug'              => 'funko-pop-harry-potter-01',
                'short_description' => 'La figura original de Harry Potter con varita y capa.',
                'description'       => 'El Funko Pop original de Harry Potter con su varita mágica y capa de mago. Una pieza clásica para coleccionistas. La figura está en oferta por tiempo limitado.',
                'price'             => 13.95,
                'original_price'    => 15.95,
                'stock'             => 50,
                'category_id'       => $catFunko,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'FNK-MTG-JAC',
                'name'              => 'Funko Pop MTG — Jace Beleren (#24)',
                'slug'              => 'funko-pop-mtg-jace-beleren-24',
                'short_description' => 'Figura Funko del planeswalker más icónico de Magic.',
                'description'       => 'Jace Beleren, el Tejedor de Mente y uno de los planeswalkers más reconocidos de Magic: The Gathering, en formato Funko Pop. Edición limitada para coleccionistas de MTG.',
                'price'             => 17.95,
                'original_price'    => null,
                'stock'             => 20,
                'category_id'       => $catFunko,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],

            // ─────────────────────────────────────────────────────────────
            // ACCESORIOS
            // ─────────────────────────────────────────────────────────────
            [
                'sku'               => 'ACC-DS-MAT',
                'name'              => 'Fundas Dragon Shield Matte — 100 unidades Standard',
                'slug'              => 'fundas-dragon-shield-matte-100-standard',
                'short_description' => 'Las mejores fundas del mercado. Matte negro, 63.5 x 88 mm.',
                'description'       => 'Las fundas Dragon Shield Matte son el estándar de oro en protección de cartas TCG. Acabado mate que elimina reflejos para la fotografía y el juego competitivo. Tamaño Standard (63.5 x 88 mm), compatibles con Pokémon, MTG, Lorcana y más.',
                'price'             => 10.95,
                'original_price'    => null,
                'stock'             => 80,
                'category_id'       => $catAccesorios,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => true,
            ],
            [
                'sku'               => 'ACC-KAT-STD',
                'name'              => 'Fundas Katana Standard — 100 unidades',
                'slug'              => 'fundas-katana-standard-100-unidades',
                'short_description' => 'Fundas económicas de alta calidad. Ideales para uso diario.',
                'description'       => 'Las fundas Katana ofrecen la mejor relación calidad-precio del mercado. Perfectas para proteger tu colección sin arruinar el presupuesto. Tamaño Standard, pack de 100 unidades.',
                'price'             => 5.95,
                'original_price'    => null,
                'stock'             => 120,
                'category_id'       => $catAccesorios,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'ACC-UP-ECL',
                'name'              => 'Deck Box Ultra Pro Eclipse — 80+ cartas',
                'slug'              => 'deck-box-ultra-pro-eclipse-80-cartas',
                'short_description' => 'Caja guardamazos premium con cierre magnético. Varias colores.',
                'description'       => 'La Deck Box Eclipse de Ultra Pro tiene un sistema de cierre magnético de alta calidad y capacidad para 80 cartas con doble funda. Interior en gamuza para protección extra. Disponible en negro, azul, rojo y verde.',
                'price'             => 12.95,
                'original_price'    => null,
                'stock'             => 45,
                'category_id'       => $catAccesorios,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'ACC-UG-ALB',
                'name'              => 'Álbum 9 bolsillos Ultimate Guard — 360 cartas',
                'slug'              => 'album-9-bolsillos-ultimate-guard-360-cartas',
                'short_description' => 'Álbum rígido de 9 bolsillos para coleccionar hasta 360 cartas.',
                'description'       => 'El álbum Flexxfolio de Ultimate Guard con 20 páginas de 9 bolsillos cada una almacena hasta 360 cartas con doble funda. Tapa rígida, anillas de alta calidad y diseño premium en color negro.',
                'price'             => 24.95,
                'original_price'    => null,
                'stock'             => 30,
                'category_id'       => $catAccesorios,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
            [
                'sku'               => 'ACC-FC-PLY',
                'name'              => 'Playmat Factory Cards — Logo Oficial Verde',
                'slug'              => 'playmat-factory-cards-logo-oficial-verde',
                'short_description' => 'Tapete de juego oficial de Factory Cards. 60 x 35 cm, antideslizante.',
                'description'       => 'El playmat oficial de Factory Cards con el logo verde de la tienda. Superficie suave para colocar cartas, base antideslizante de neopreno. Medidas: 60 x 35 cm. Incluye bolsa de transporte.',
                'price'             => 18.95,
                'original_price'    => null,
                'stock'             => 25,
                'category_id'       => $catAccesorios,
                'franchise_id'      => null,
                'status'            => 'active',
                'is_featured'       => false,
            ],
        ];

        // Insertar todos los productos usando firstOrCreate por SKU.
        // Si ya existe el SKU, no lo sobreescribimos (respetamos precio/stock actualizados manualmente).
        foreach ($productos as $p) {
            $sku = $p['sku'];
            unset($p['sku']);
            Product::firstOrCreate(['sku' => $sku], array_merge($p, ['sku' => $sku]));
        }

        // ══════════════════════════════════════════════════════════════════
        // 6. INVALIDAR CACHÉ DEL HEADER
        // ViewServiceProvider cachea franquicias y categorías 5 minutos.
        // Las olvidamos para que el próximo request las recargue actualizadas.
        // ══════════════════════════════════════════════════════════════════
        Cache::forget('header_categories');
        Cache::forget('header_franchises');

        $this->command->info('✅ CatalogSeeder ejecutado: ' . count($productos) . ' productos, ' . count($franquicias) . ' franquicias, ' . (count($categoriasRaiz) + count($subTCG) + count($subWar)) . ' categorías.');
    }
}
