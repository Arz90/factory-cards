{{--
    Vista: admin/events/form
    Propósito: Formulario compartido de creación y edición de eventos.
    La variable $event existe en edición y no existe en creación.
--}}
@extends('layouts.admin')
@section('title', isset($event) ? 'Editar evento' : 'Nuevo evento')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ isset($event) ? 'Editar evento' : 'Nuevo evento' }}</h4>
    <a href="{{ route('admin.eventos.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<form action="{{ isset($event) ? route('admin.eventos.update', $event->id) : route('admin.eventos.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if(isset($event)) @method('PUT') @endif

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">

        {{-- ── Columna principal ── --}}
        <div class="col-lg-8">

            {{-- Información básica --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Información del evento</h6>
                </div>
                <div class="card-body">

                    {{-- Título --}}
                    <div class="mb-3">
                        <label class="form-label">Título del evento <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $event->title ?? '') }}"
                               placeholder="Ej: MAGIC: PRESENTACIÓN EL HOBBIT"
                               required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-0">
                        <label class="form-label">Descripción completa</label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="8"
                                  placeholder="Horarios, premios, detalles de inscripción... Los saltos de línea se respetarán en la web.">{{ old('description', $event->description ?? '') }}</textarea>
                        <div class="form-text">Los saltos de línea se mostrarán correctamente en la tienda.</div>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- Fechas --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Fechas</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Fecha y hora de inicio <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', isset($event) ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
                                   required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Fecha y hora de fin</label>
                            <input type="datetime-local"
                                   name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Columna lateral ── --}}
        <div class="col-lg-4">

            {{-- Publicación --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Publicación</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               value="1"
                               id="is_active"
                               @checked(old('is_active', $event->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Evento activo (visible en la web)</label>
                    </div>
                </div>
            </div>

            {{-- Precio --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Inscripción</h6>
                </div>
                <div class="card-body">
                    <label class="form-label">Precio de inscripción</label>
                    <div class="input-group">
                        <input type="number"
                               name="price"
                               step="0.01"
                               min="0"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $event->price ?? '') }}"
                               placeholder="0.00">
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="form-text">Déjalo vacío si el evento es gratuito.</div>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Ubicación</h6>
                </div>
                <div class="card-body">
                    <label class="form-label">
                        <i class="bi bi-geo-alt me-1"></i>Enlace Google Maps
                    </label>
                    <input type="url"
                           name="google_maps_url"
                           class="form-control @error('google_maps_url') is-invalid @enderror"
                           value="{{ old('google_maps_url', $event->google_maps_url ?? '') }}"
                           placeholder="https://maps.google.com/...">
                    @error('google_maps_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Cartel / Imagen --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Cartel del evento</h6>
                </div>
                <div class="card-body">

                    {{-- Vista previa de imagen actual --}}
                    @if(isset($event) && $event->image_path)
                        <img src="{{ $event->urlImagen() }}"
                             class="img-fluid rounded mb-2"
                             alt="Cartel actual"
                             id="preview-imagen">
                    @else
                        <img src="" class="img-fluid rounded mb-2 d-none" alt="Vista previa" id="preview-imagen">
                    @endif

                    <input type="file"
                           name="image"
                           class="form-control form-control-sm @error('image') is-invalid @enderror"
                           accept="image/*"
                           id="input-imagen">
                    <div class="form-text">JPG, PNG, WebP. Máx 6 MB. Proporción recomendada: 16:9.</div>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($event) ? 'Guardar cambios' : 'Crear evento' }}
                </button>
                <a href="{{ route('admin.eventos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Vista previa de la imagen al seleccionarla
document.getElementById('input-imagen').addEventListener('change', function () {
    const preview = document.getElementById('preview-imagen');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
