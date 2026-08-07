<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    /**
     * Portada de la tienda pública.
     * Carga los banners activos para el hero slider y los productos destacados.
     */
    public function index()
    {
        try {
            // Banners activos ordenados por su campo 'order' (de menor a mayor)
            $banners = Banner::activos()->ordenados()->get();

            $featured  = Product::with(['franchise', 'category'])->featured()->active()->inStock()->limit(8)->get();
            $preorders = Product::with(['franchise', 'category'])->preorder()->limit(4)->get();

            Log::info('Portada cargada', [
                'banners_activos'    => $banners->count(),
                'productos_featured' => $featured->count(),
            ]);

        } catch (\Throwable $e) {
            // Si falla la carga de banners no rompemos la portada
            Log::error('Error al cargar banners en la portada', ['error' => $e->getMessage()]);
            $banners   = collect();
            $featured  = collect();
            $preorders = collect();
        }

        return view('shop.home', compact('banners', 'featured', 'preorders'));
    }

    public function catalog(Request $request)
    {
        $query = Product::with(['franchise', 'category'])->whereIn('status', ['active', 'preorder']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('franchise')) {
            $query->whereHas('franchise', fn($q) => $q->where('slug', $request->franchise));
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $products   = $query->orderByDesc('is_featured')->orderByDesc('created_at')->paginate(24)->withQueryString();
        $franchises = Franchise::active()->get();
        $categories = Category::active()->roots()->get();

        return view('shop.catalog', compact('products', 'franchises', 'categories'));
    }

    public function show(string $slug)
    {
        $product  = Product::with(['franchise', 'category'])->where('slug', $slug)->firstOrFail();
        $related  = Product::with('franchise')
            ->where('franchise_id', $product->franchise_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->limit(4)
            ->get();
        return view('shop.product', compact('product', 'related'));
    }

    public function byFranchise(string $slug)
    {
        $franchise = Franchise::where('slug', $slug)->firstOrFail();
        $products  = Product::with('category')
            ->where('franchise_id', $franchise->id)
            ->whereIn('status', ['active', 'preorder'])
            ->paginate(24);
        return view('shop.catalog', ['products' => $products, 'franchise' => $franchise, 'franchises' => Franchise::active()->get(), 'categories' => collect()]);
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::with('franchise')
            ->where('category_id', $category->id)
            ->whereIn('status', ['active', 'preorder'])
            ->paginate(24);
        return view('shop.catalog', ['products' => $products, 'category' => $category, 'franchises' => Franchise::active()->get(), 'categories' => Category::active()->roots()->get()]);
    }

    public function search(Request $request)
    {
        return $this->catalog($request);
    }
}
