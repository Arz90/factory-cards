<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FranchiseController extends Controller
{
    public function index()
    {
        $franchises = Franchise::orderBy('sort_order')->paginate(20);
        return view('admin.franchises.index', compact('franchises'));
    }

    public function create()
    {
        return view('admin.franchises.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['name']);
        Franchise::create($data);
        return redirect()->route('admin.franquicias.index')->with('success', 'Franquicia creada.');
    }

    public function edit(Franchise $franquicia)
    {
        return view('admin.franchises.form', ['franchise' => $franquicia]);
    }

    public function update(Request $request, Franchise $franquicia)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
        ]);
        $franquicia->update($data);
        return redirect()->route('admin.franquicias.index')->with('success', 'Franquicia actualizada.');
    }

    public function destroy(Franchise $franquicia)
    {
        $franquicia->delete();
        return redirect()->route('admin.franquicias.index')->with('success', 'Franquicia eliminada.');
    }
}
