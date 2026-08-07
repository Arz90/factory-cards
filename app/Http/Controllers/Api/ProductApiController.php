<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category:id,name,slug', 'franchise:id,name,slug,color'])
            ->whereIn('status', ['active', 'preorder']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('franchise')) {
            $query->whereHas('franchise', fn($q) => $q->where('slug', $request->franchise));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(24);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'franchise'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => $product]);
    }

    public function categories()
    {
        $categories = Category::active()
            ->roots()
            ->with('children')
            ->get(['id', 'name', 'slug', 'parent_id', 'sort_order']);

        return response()->json(['data' => $categories]);
    }

    public function franchises()
    {
        $franchises = Franchise::active()
            ->get(['id', 'name', 'slug', 'color', 'sort_order']);

        return response()->json(['data' => $franchises]);
    }
}
