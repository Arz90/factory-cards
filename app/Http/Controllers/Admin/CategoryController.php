<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada.');
    }

    public function edit(Category $categorium)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $categorium->id)->orderBy('name')->get();
        return view('admin.categories.form', ['category' => $categorium, 'parents' => $parents]);
    }

    public function update(Request $request, Category $categorium)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
        ]);
        $categorium->update($data);
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $categorium)
    {
        $categorium->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada.');
    }
}
