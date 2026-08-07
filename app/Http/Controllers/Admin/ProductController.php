<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // Validación manual de extensión si se adjuntó imagen
        if ($request->hasFile('image')) {
            $errorExt = $this->validarExtensionImagen($request->file('image'));
            if ($errorExt) {
                return back()->withInput()->withErrors(['image' => $errorExt]);
            }
        }

        $data['slug']      = Str::slug($data['name']);
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
            // Validación manual de extensión sin fileinfo
            $errorExt = $this->validarExtensionImagen($request->file('image'));
            if ($errorExt) {
                return back()->withInput()->withErrors(['image' => $errorExt]);
            }
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
            // Sin 'image' ni 'mimes': ambas reglas requieren la extensión fileinfo,
            // que está bloqueada por Windows App Control. La extensión se valida manualmente.
            'image'             => 'nullable|max:4096',
        ]);
    }

    /**
     * Valida la extensión del archivo de imagen sin usar fileinfo/MIME detection.
     * Extensiones aceptadas: jpg, jpeg, png, webp.
     *
     * @return string|null  Mensaje de error o null si es válida.
     */
    private function validarExtensionImagen($archivo): ?string
    {
        if (!$archivo || !$archivo->isValid()) {
            return 'El archivo no es válido o hubo un error al subirlo.';
        }

        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($extension, $extensionesPermitidas, true)) {
            return 'Solo se aceptan imágenes JPG, JPEG, PNG o WebP. '
                 . "Se recibió: \".{$extension}\"";
        }

        return null;
    }

    /**
     * Sube la imagen del producto a public/images/products/ y devuelve la ruta relativa.
     *
     * Limpia el nombre para evitar dobles extensiones (ej: foto.jpg.webp → foto.webp)
     * y caracteres especiales que puedan causar problemas en el sistema de archivos.
     */
    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $archivo   = $request->file('image');
        $extension = strtolower($archivo->getClientOriginalExtension());

        // Extraer el stem del nombre original y quitar cualquier extensión anidada
        // Ejemplo: "producto.jpg.webp" → stem="producto.jpg" → limpio="producto"
        $stemOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $stemLimpio   = preg_replace('/\.[a-z0-9]+$/i', '', $stemOriginal);
        $stemSeguro   = Str::slug($stemLimpio) ?: 'producto';

        $nombreFinal = time() . '_' . $stemSeguro . '.' . $extension;

        $carpeta = public_path('images/products');
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $archivo->move($carpeta, $nombreFinal);

        Log::info('Imagen de producto subida', ['archivo' => $nombreFinal]);

        return 'images/products/' . $nombreFinal;
    }
}
