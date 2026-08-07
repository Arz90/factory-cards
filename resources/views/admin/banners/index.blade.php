{{--
    Vista: admin/banners/index
    Propósito: Listado de todos los banners del hero slider.
    Permite activar/desactivar inline, editar y eliminar.
--}}
@extends('layouts.admin')
@section('title', 'Banners — Hero Slider')

@section('content')

{{-- ── Cabecera de sección ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Hero Slider — Banners</h4>
        <p class="text-muted small mb-0">
            Gestiona los slides del carrusel principal de la portada.
            El orden se controla con el campo "Posición" de cada banner.
        </p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo banner
    </a>
</div>

{{-- ── Tabla de banners ── --}}
@if($banners->isEmpty())
    {{-- Estado vacío --}}
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
        <p class="text-muted mt-3 mb-3">No hay banners creados todavía.</p>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm mx-auto" style="width:fit-content">
            Crear primer banner
        </a>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">Pos.</th>
                        <th style="width:100px">Imagen</th>
                        <th>Título</th>
                        <th>Botón</th>
                        <th>Enlace</th>
                        <th class="text-center">Activo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr id="fila-banner-{{ $banner->id }}">

                        {{-- Posición numérica --}}
                        <td class="text-muted fw-bold text-center">{{ $banner->order }}</td>

                        {{-- Miniatura de la imagen --}}
                        <td>
                            <img src="{{ $banner->urlImagen() }}"
                                 alt="{{ $banner->title }}"
                                 class="rounded"
                                 style="width:80px;height:48px;object-fit:cover;">
                        </td>

                        {{-- Título y subtítulo --}}
                        <td>
                            <div class="fw-semibold">{{ $banner->title }}</div>
                            @if($banner->subtitle)
                                <div class="text-muted small text-truncate" style="max-width:220px">
                                    {{ $banner->subtitle }}
                                </div>
                            @endif
                        </td>

                        {{-- Texto del botón --}}
                        <td>
                            <span class="badge bg-secondary">{{ $banner->button_text }}</span>
                        </td>

                        {{-- URL de destino --}}
                        <td>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank"
                                   class="text-muted small text-truncate d-block" style="max-width:160px">
                                    <i class="bi bi-link-45deg me-1"></i>{{ $banner->link_url }}
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        {{-- Toggle activo/inactivo (AJAX) --}}
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center mb-0">
                                <input
                                    class="form-check-input toggle-activo"
                                    type="checkbox"
                                    role="switch"
                                    data-banner-id="{{ $banner->id }}"
                                    data-url="{{ route('admin.banners.toggle', $banner) }}"
                                    {{ $banner->is_active ? 'checked' : '' }}
                                    style="cursor:pointer"
                                >
                            </div>
                        </td>

                        {{-- Acciones --}}
                        <td class="text-end">
                            <a href="{{ route('admin.banners.edit', $banner) }}"
                               class="btn btn-sm btn-outline-secondary me-1"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('admin.banners.destroy', $banner) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este banner? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
/**
 * Toggle activo/inactivo de banners mediante AJAX.
 * Actualiza el switch visualmente sin recargar la página.
 */
document.querySelectorAll('.toggle-activo').forEach(function(toggle) {
    toggle.addEventListener('change', async function() {
        const bannerId = this.dataset.bannerId;
        const url      = this.dataset.url;
        const fila     = document.getElementById('fila-banner-' + bannerId);

        try {
            const respuesta = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json',
                },
            });

            if (!respuesta.ok) {
                throw new Error('Error HTTP ' + respuesta.status);
            }

            const datos = await respuesta.json();

            // Actualizar estilo visual de la fila según estado
            if (datos.is_active) {
                fila.classList.remove('table-secondary', 'opacity-75');
            } else {
                fila.classList.add('table-secondary', 'opacity-75');
            }

            console.log('Estado banner', bannerId, ':', datos.mensaje);

        } catch (error) {
            // Revertir el switch si falló la petición
            this.checked = !this.checked;
            console.error('Error al cambiar estado del banner:', error);
            alert('No se pudo cambiar el estado del banner. Inténtalo de nuevo.');
        }
    });

    // Aplicar estilo inicial a filas inactivas
    if (!toggle.checked) {
        const bannerId = toggle.dataset.bannerId;
        document.getElementById('fila-banner-' + bannerId)?.classList.add('table-secondary', 'opacity-75');
    }
});
</script>
@endpush
