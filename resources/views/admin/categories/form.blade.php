@extends('layouts.admin')
@section('title', isset($category) ? 'Editar categoría' : 'Nueva categoría')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ isset($category) ? 'Editar categoría' : 'Nueva categoría' }}</h4>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ isset($category) ? route('admin.categorias.update', $category) : route('admin.categorias.store') }}" method="POST">
            @csrf @if(isset($category)) @method('PUT') @endif
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <div class="mb-3">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría padre</label>
                <select name="parent_id" class="form-select">
                    <option value="">Sin padre (raíz)</option>
                    @foreach($parents as $p)
                    <option value="{{ $p->id }}" @selected(old('parent_id', $category->parent_id ?? '')==$p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Orden</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
                <label class="form-check-label">Activa</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Guardar' : 'Crear' }}</button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
