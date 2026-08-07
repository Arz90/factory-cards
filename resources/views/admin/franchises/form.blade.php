@extends('layouts.admin')
@section('title', isset($franchise) ? 'Editar franquicia' : 'Nueva franquicia')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ isset($franchise) ? 'Editar franquicia' : 'Nueva franquicia' }}</h4>
    <a href="{{ route('admin.franquicias.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>
<div class="card shadow-sm" style="max-width:500px">
    <div class="card-body">
        <form action="{{ isset($franchise) ? route('admin.franquicias.update', $franchise) : route('admin.franquicias.store') }}" method="POST">
            @csrf @if(isset($franchise)) @method('PUT') @endif
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <div class="mb-3">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $franchise->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Color (hex)</label>
                <div class="input-group">
                    <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $franchise->color ?? '#000000') }}" style="width:60px">
                    <input type="text" class="form-control" value="{{ old('color', $franchise->color ?? '') }}" placeholder="#FFCB05" readonly>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Orden</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $franchise->sort_order ?? 0) }}">
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $franchise->is_active ?? true))>
                <label class="form-check-label">Activa</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ isset($franchise) ? 'Guardar' : 'Crear' }}</button>
                <a href="{{ route('admin.franquicias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
