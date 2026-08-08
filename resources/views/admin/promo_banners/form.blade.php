@extends('layouts.admin')
@section('title', isset($banner->id) ? 'Editar promo banner' : 'Nuevo promo banner')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">{{ isset($banner->id) ? 'Editar promo banner' : 'Nuevo promo banner' }}</h4>
    <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<form action="{{ isset($banner->id) ? route('admin.promo-banners.update', $banner->id) : route('admin.promo-banners.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($banner->id)) @method('PUT') @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">

        {{-- Columna principal --}}
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Contenido del banner</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Etiqueta de franquicia</label>
                        <input type="text" name="franchise_label"
                               class="form-control"
                               placeholder="Ej: MAGIC: THE GATHERING"
                               value="{{ old('franchise_label', $banner->franchise_label ?? '') }}">
                        <div class="form-text">Texto pequeño que aparece encima del título.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Ej: SECRETOS DE STRIXHAVEN"
                               value="{{ old('title', $banner->title ?? '') }}" required>
                        <div class="form-text">Se muestra en mayúsculas y tamaño grande. Puedes usar salto de línea escribiendo &lt;br&gt; pero el CSS ya rompe en dos líneas automáticamente.</div>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  maxlength="1000">{{ old('description', $banner->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Botón de acción</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-5">
                            <label class="form-label">Texto del botón <span class="text-danger">*</span></label>
                            <input type="text" name="button_text"
                                   class="form-control @error('button_text') is-invalid @enderror"
                                   placeholder="Ej: PRECOMPRA AHORA"
                                   value="{{ old('button_text', $banner->button_text ?? 'VER PRODUCTO') }}" required>
                            @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label">URL del botón <span class="text-danger">*</span></label>
                            <input type="text" name="button_url"
                                   class="form-control @error('button_url') is-invalid @enderror"
                                   placeholder="Ej: /tienda  o  /producto/strixhaven"
                                   value="{{ old('button_url', $banner->button_url ?? '/tienda') }}" required>
                            @error('button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna lateral --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Publicación</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha de lanzamiento</label>
                        <input type="date" name="launch_date"
                               class="form-control"
                               value="{{ old('launch_date', isset($banner->launch_date) ? $banner->launch_date->format('Y-m-d') : '') }}">
                        <div class="form-text">Opcional. Se muestra como "Lanzamiento: DD de mes de AAAA".</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               name="is_active" value="1" id="is_active"
                               @checked(old('is_active', $banner->is_active ?? false))>
                        <label class="form-check-label" for="is_active">
                            Activo en portada
                        </label>
                    </div>
                    <div class="form-text">Al activar este banner se desactivarán los demás.</div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Imagen (lado derecho)</h6>
                </div>
                <div class="card-body">
                    @if(isset($banner->image_path) && $banner->image_path)
                        <img src="{{ asset($banner->image_path) }}"
                             class="img-fluid rounded mb-2" alt="">
                    @endif
                    <input type="file" name="imagen"
                           class="form-control @error('imagen') is-invalid @enderror"
                           accept="image/*">
                    @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="text-muted mt-1" style="font-size:.75rem">
                        JPG, PNG, WebP. Máx 4 MB. Recomendado: 800×560 px.
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($banner->id) ? 'Guardar cambios' : 'Crear promo banner' }}
                </button>
                <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>

    </div>
</form>

@endsection
