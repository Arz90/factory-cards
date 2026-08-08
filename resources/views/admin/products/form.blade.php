@extends('layouts.admin')
@section('title', isset($product) ? 'Editar producto' : 'Nuevo producto')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">{{ isset($product) ? 'Editar producto' : 'Nuevo producto' }}</h4>
    </div>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

{{-- Pasamos el ID explícitamente (no el modelo) para evitar UrlGenerationException
     cuando Laravel no puede resolver el parámetro {producto} desde el objeto --}}
<form action="{{ isset($product) ? route('admin.productos.update', $product->id) : route('admin.productos.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        {{-- Columna principal — ocupa todo en móvil, 8/12 en desktop --}}
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Información básica</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción corta</label>
                        <input type="text" name="short_description" class="form-control"
                               value="{{ old('short_description', $product->short_description ?? '') }}" maxlength="500">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción completa</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Precios y stock</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Precio venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price ?? '') }}" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Precio original (tachado)</label>
                            <div class="input-group">
                                <input type="number" name="original_price" step="0.01" min="0" class="form-control"
                                       value="{{ old('original_price', $product->original_price ?? '') }}">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Coste (privado)</label>
                            <div class="input-group">
                                <input type="number" name="cost_price" step="0.01" min="0" class="form-control"
                                       value="{{ old('cost_price', $product->cost_price ?? '') }}">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $product->stock ?? 0) }}" required>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control"
                                   value="{{ old('sku', $product->sku ?? '') }}">
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" name="weight" step="0.001" min="0" class="form-control"
                                   value="{{ old('weight', $product->weight ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna lateral — ocupa todo en móvil, 4/12 en desktop --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Publicación</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active"   @selected(old('status', $product->status ?? 'active')==='active')>Activo</option>
                            <option value="inactive" @selected(old('status', $product->status ?? '')==='inactive')>Inactivo</option>
                            <option value="preorder" @selected(old('status', $product->status ?? '')==='preorder')>Precompra</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured"
                               @checked(old('is_featured', $product->is_featured ?? false))>
                        <label class="form-check-label" for="featured">Producto destacado</label>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Clasificación</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Seleccionar...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '')==$cat->id)>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Franquicia</label>
                        <select name="franchise_id" class="form-select">
                            <option value="">Sin franquicia</option>
                            @foreach($franchises as $f)
                            <option value="{{ $f->id }}" @selected(old('franchise_id', $product->franchise_id ?? '')==$f->id)>
                                {{ $f->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Imagen principal</h6></div>
                <div class="card-body">
                    @if(isset($product) && $product->image_url)
                        <img src="{{ asset($product->image_url) }}" class="img-fluid rounded mb-2" alt="">
                    @endif
                    {{-- accept="image/*" + capture="environment" abre el carrete/cámara en móvil --}}
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/*"
                           capture="environment">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="text-muted mt-1" style="font-size:.75rem">JPG, PNG. Máx 4 MB.</div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($product) ? 'Guardar cambios' : 'Crear producto' }}
                </button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>

@endsection
