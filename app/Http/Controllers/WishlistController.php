<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Muestra todos los productos en la lista de deseos del usuario autenticado.
     */
    public function index()
    {
        // Cargamos los productos con sus relaciones para evitar N+1 queries
        $productos = auth()->user()
            ->wishlistProducts()
            ->with(['franchise', 'category'])
            ->whereIn('status', ['active', 'preorder'])
            ->orderByDesc('wishlists.created_at')
            ->get();

        return view('wishlist.index', compact('productos'));
    }

    /**
     * Añade o elimina un producto de la lista de deseos (toggle).
     * Responde siempre con JSON para el cliente AJAX.
     */
    public function toggle(Request $request, Product $product)
    {
        $usuario = auth()->user();

        // Buscar si ya existe el favorito
        $existente = Wishlist::where('user_id', $usuario->id)
                             ->where('product_id', $product->id)
                             ->first();

        if ($existente) {
            // Ya estaba en favoritos → eliminar
            $existente->delete();
            $añadido = false;
            $mensaje = '"' . $product->name . '" eliminado de tu lista de deseos.';
        } else {
            // No estaba → añadir
            Wishlist::create([
                'user_id'    => $usuario->id,
                'product_id' => $product->id,
            ]);
            $añadido = true;
            $mensaje = '"' . $product->name . '" añadido a tu lista de deseos.';
        }

        Log::info('Wishlist toggle', [
            'usuario_id'  => $usuario->id,
            'producto_id' => $product->id,
            'accion'      => $añadido ? 'añadido' : 'eliminado',
        ]);

        return response()->json([
            'ok'      => true,
            'añadido' => $añadido,
            'mensaje' => $mensaje,
        ]);
    }
}
