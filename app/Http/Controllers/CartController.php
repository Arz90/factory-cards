<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = $this->getCartItems();
        // Para usuarios: CartItem objects con relación product (usamos lineTotal()).
        // Para guests: arrays de sesión con clave 'price'.
        $total = auth()->check()
            ? $items->sum(fn($i) => $i->lineTotal())
            : $items->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0));
        return view('shop.cart', compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'integer|min:1|max:99']);
        $qty = $request->integer('quantity', 1);

        if (auth()->check()) {
            $item = CartItem::firstOrNew([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ]);
            $item->quantity = ($item->quantity ?? 0) + $qty;
            $item->save();
        } else {
            $cart = session('cart', []);
            $key  = "p_{$product->id}";
            $cart[$key] = [
                'product_id' => $product->id,
                'quantity'   => ($cart[$key]['quantity'] ?? 0) + $qty,
                'price'      => $product->price,
                'name'       => $product->name,
                'image_url'  => $product->image_url,
            ];
            session(['cart' => $cart, 'cart_count' => collect($cart)->sum('quantity')]);
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', '"' . $product->name . '" añadido al carrito.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);
        $cartItem->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'quantity' => $cartItem->quantity]);
        }
        return back();
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget(['cart', 'cart_count']);
        }
        return back()->with('success', 'Carrito vaciado.');
    }

    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::with('product')->where('user_id', auth()->id())->get();
        }
        return collect(session('cart', []));
    }
}
