@extends('layouts.admin')
@section('title', 'Promo Banners')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Promo Banners</h4>
        <p class="text-muted small mb-0">Sección destacada split 50/50 de la portada. Solo un banner puede estar activo.</p>
    </div>
    <a href="{{ route('admin.promo-banners.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nuevo promo banner
    </a>
</div>

@if($banners->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-layout-split fs-1 d-block mb-3"></i>
            <p class="mb-0">No hay promo banners todavía. <a href="{{ route('admin.promo-banners.create') }}">Crea el primero.</a></p>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Franquicia</th>
                        <th>Lanzamiento</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr>
                        <td style="width:80px">
                            @if($banner->image_path)
                                <img src="{{ asset($banner->image_path) }}"
                                     alt="{{ $banner->title }}"
                                     class="rounded"
                                     style="width:72px;height:48px;object-fit:cover;">
                            @else
                                <div class="rounded bg-secondary d-flex align-items-center justify-content-center"
                                     style="width:72px;height:48px;">
                                    <i class="bi bi-image text-white small"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $banner->title }}</span>
                            <br>
                            <span class="text-muted small text-truncate d-inline-block" style="max-width:220px">
                                {{ $banner->description }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $banner->franchise_label ?: '—' }}</span>
                        </td>
                        <td class="small">
                            {{ $banner->fechaFormateada() ?? '—' }}
                        </td>
                        <td class="text-center">
                            {{-- Toggle activo/inactivo vía AJAX --}}
                            <div class="form-check form-switch d-flex justify-content-center mb-0">
                                <input class="form-check-input toggle-promo"
                                       type="checkbox"
                                       role="switch"
                                       data-id="{{ $banner->id }}"
                                       {{ $banner->is_active ? 'checked' : '' }}
                                       title="{{ $banner->is_active ? 'Activo' : 'Inactivo' }}">
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.promo-banners.edit', $banner->id) }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.promo-banners.destroy', $banner->id) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm btn-swal-delete"
                                        data-nombre="{{ $banner->title }}">
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
// Toggle activo/inactivo del promo banner vía AJAX
document.querySelectorAll('.toggle-promo').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
        const id       = this.dataset.id;
        const checked  = this.checked;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/admin/promo-banners/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                // Si se activó este, desactivar visualmente todos los demás
                if (data.is_active) {
                    document.querySelectorAll('.toggle-promo').forEach(function (t) {
                        if (t.dataset.id !== id) t.checked = false;
                    });
                }
            } else {
                toggle.checked = !checked; // revertir si hubo error
            }
        })
        .catch(function () {
            toggle.checked = !checked;
        });
    });
});
</script>
@endpush
