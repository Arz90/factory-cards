<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'franchise'])->orderByDesc('created_at');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('franchise_id')) {
            $query->where('franchise_id', $request->franchise_id);
        }

        $products   = $query->paginate(20)->withQueryString();
        $franchises = Franchise::active()->get();

        return view('admin.products.index', compact('products', 'franchises'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $franchises = Franchise::active()->get();
        return view('admin.products.form', compact('categories', 'franchises'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']);
        $data['image_url'] = $this->handleImageUpload($request);

        Product::create($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $franchises = Franchise::active()->get();
        return view('admin.products.form', compact('product', 'categories', 'franchises'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->handleImageUpload($request);
        }

        $product->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $product->update(['stock' => $request->stock]);

        return response()->json(['ok' => true, 'stock' => $product->stock]);
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json(['ok' => true, 'is_featured' => $product->is_featured]);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'original_price'    => 'nullable|numeric|min:0',
            'cost_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100',
            'category_id'       => 'required|exists:categories,id',
            'franchise_id'      => 'nullable|exists:franchises,id',
            'status'            => 'required|in:active,inactive,preorder',
            'is_featured'       => 'boolean',
            'weight'            => 'nullable|numeric|min:0',
            'image'             => 'nullable|image|max:4096',
        ]);
    }

    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/products'), $name);

        return 'images/products/' . $name;
    }
}
