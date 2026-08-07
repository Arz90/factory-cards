{{--
    Vista: admin/banners/form (compartida para crear y editar)
    Propósito: Formulario de alta/edición de un banner del hero slider.
    $banner se pasa siempre — nuevo (sin id) para crear, con id para editar.
--}}
@extends('layouts.admin')
@section('title', $banner->exists ? 'Editar Banner' : 'Nuevo Banner')

@section('content')

{{-- ── Cabecera con migas ── --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">
        {{ $banner->exists ? 'Editar Banner' : 'Nuevo Banner' }}
    </h4>
</div>

<div class="row g-4">

    {{-- ── Columna principal: formulario ── --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form
                    action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if($banner->exists)
                        @method('PUT')
                    @endif

                    {{-- ── Campo: Título ── --}}
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">
                            Título <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $banner->title) }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Ej: Próximos Lanzamientos TCG"
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">
                            Texto grande visible en el slide del slider.
                        </div>
                    </div>

                    {{-- ── Campo: Subtítulo ── --}}
                    <div class="mb-3">
                        <label for="subtitle" class="form-label fw-semibold">Subtítulo</label>
                        <input
                            type="text"
                            id="subtitle"
                            name="subtitle"
                            value="{{ old('subtitle', $banner->subtitle) }}"
                            class="form-control @error('subtitle') is-invalid @enderror"
                            placeholder="Ej: Reserva antes del lanzamiento al mejor precio"
                        >
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ── Campo: Imagen ── --}}
                    <div class="mb-3">
                        <label for="imagen" class="form-label fw-semibold">
                            Imagen del banner
                            @if(!$banner->exists) <span class="text-danger">*</span> @endif
                        </label>

                        {{-- Vista previa de la imagen actual (solo al editar) --}}
                        @if($banner->exists && $banner->image_path)
                            <div class="mb-2">
                                <img src="{{ $banner->urlImagen() }}"
                                     alt="Imagen actual"
                                     class="img-fluid rounded"
                                     style="max-height:120px; object-fit:cover;"
                                     id="previsualizacion-actual">
                                <div class="form-text">Imagen actual. Sube una nueva para reemplazarla.</div>
                            </div>
                        @endif

                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept="image/jpeg,image/png,image/webp"
                            class="form-control @error('imagen') is-invalid @enderror"
                            {{ !$banner->exists ? 'required' : '' }}
                            onchange="previsualizarImagen(this)"
                        >
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">
                            JPG, PNG o WebP. Máximo 3 MB. Tamaño recomendado: 1600 × 520 px.
                        </div>

                        {{-- Vista previa de nueva imagen seleccionada --}}
                        <img id="previa-nueva-imagen"
                             src="#"
                             alt="Vista previa"
                             class="img-fluid rounded mt-2 d-none"
                             style="max-height:120px; object-fit:cover;">
                    </div>

                    {{-- ── Campos en fila: Botón + Orden ── --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="button_text" class="form-label fw-semibold">
                                Texto del botón <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="button_text"
                                name="button_text"
                                value="{{ old('button_text', $banner->button_text ?? 'VER MÁS') }}"
                                class="form-control @error('button_text') is-invalid @enderror"
                                placeholder="Ej: PRECOMPRA, VER AHORA"
                                maxlength="50"
                                required
                            >
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="order" class="form-label fw-semibold">
                                Posición (orden) <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                id="order"
                                name="order"
                                value="{{ old('order', $banner->order ?? 0) }}"
                                class="form-control @error('order') is-invalid @enderror"
                                min="0"
                                max="99"
                                required
                            >
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">0 = primero. Valores bajos aparecen antes.</div>
                        </div>
                    </div>

                    {{-- ── Campo: URL de destino ── --}}
                    <div class="mb-3">
                        <label for="link_url" class="form-label fw-semibold">URL de destino</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                            <input
                                type="url"
                                id="link_url"
                                name="link_url"
                                value="{{ old('link_url', $banner->link_url) }}"
                                class="form-control @error('link_url') is-invalid @enderror"
                                placeholder="https://factorycards.com/tienda/..."
                            >
                            @error('link_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text text-muted">
                            Página a la que dirige el botón del banner. Dejar vacío si no enlaza.
                        </div>
                    </div>

                    {{-- ── Toggle: Activo / Inactivo ── --}}
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}
                            >
                            <label class="form-check-label fw-semibold" for="is_active">
                                Banner activo (visible en la portada)
                            </label>
                        </div>
                    </div>

                    {{-- ── Botones de acción ── --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ $banner->exists ? 'Guardar cambios' : 'Crear banner' }}
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ── Columna lateral: ayuda ── --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">
                <i class="bi bi-info-circle me-2 text-primary"></i>Consejos
            </div>
            <div class="card-body small text-muted">
                <ul class="mb-0 ps-3">
                    <li class="mb-2">
                        <strong>Posición:</strong> El número más bajo aparece primero en el slider.
                        Usa 0, 1, 2... para controlar el orden exacto.
                    </li>
                    <li class="mb-2">
                        <strong>Imagen:</strong> Usa imágenes en horizontal (landscape),
                        mínimo 1200 px de ancho para que se vean bien en pantallas grandes.
                    </li>
                    <li class="mb-2">
                        <strong>Título:</strong> Mantén el texto corto (máx. 4-5 palabras)
                        para que se lea bien sobre el fondo oscuro del slide.
                    </li>
                    <li>
                        <strong>Inactivo:</strong> Un banner inactivo se guarda pero no aparece
                        en la portada. Úsalo para preparar campañas con antelación.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
/**
 * Muestra una vista previa de la imagen seleccionada antes de subirla.
 */
function previsualizarImagen(inputFile) {
    const previa = document.getElementById('previa-nueva-imagen');
    const actual = document.getElementById('previsualizacion-actual');

    if (inputFile.files && inputFile.files[0]) {
        const lector = new FileReader();

        lector.onload = function(e) {
            previa.src = e.target.result;
            previa.classList.remove('d-none');
            // Ocultar imagen actual para que la previa quede más clara
            if (actual) actual.classList.add('d-none');
        };

        lector.readAsDataURL(inputFile.files[0]);
    }
}
</script>
@endpush
