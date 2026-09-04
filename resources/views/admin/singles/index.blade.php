{{--
    Vista: admin/singles/index
    Propósito: Panel de administración de Singles.
    Muestra estadísticas de la tabla y permite lanzar sincronizaciones
    con la API de Pokémon TCG (pokemontcg.io) bajo demanda.
--}}
@extends('layouts.admin')

@section('title', 'Singles — Gestión y Sincronización')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">Singles — Cartas Individuales</h1>
        <p class="text-muted small mb-0">Sincronización con la API de Pokémon TCG (pokemontcg.io)</p>
    </div>
</div>

{{-- Alertas de sesión --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{!! session('success') !!}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{!! session('error') !!}
    </div>
@endif

<div class="row g-4 mb-4">

    {{-- ── Tarjetas de estadísticas ── --}}
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#e8f5e9;font-size:1.4rem;">
                    <i class="bi bi-collection-fill text-success"></i>
                </div>
                <div>
                    <div class="h3 fw-black mb-0">{{ number_format($stats['total']) }}</div>
                    <div class="text-muted small">Singles en BD</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#e3f2fd;font-size:1.4rem;">
                    <i class="bi bi-check-circle-fill" style="color:#1976D2"></i>
                </div>
                <div>
                    <div class="h3 fw-black mb-0">{{ number_format($stats['activas']) }}</div>
                    <div class="text-muted small">Activas (visibles)</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#fff3e0;font-size:1.4rem;">
                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                </div>
                <div>
                    <div class="h3 fw-black mb-0">{{ number_format($stats['sin_precio']) }}</div>
                    <div class="text-muted small">Con precio mínimo (≤ 0,02 €)</div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">

    {{-- ── Formulario de sincronización ── --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom fw-bold">
                <i class="bi bi-cloud-download-fill me-2 text-success"></i>
                Sincronización bajo demanda
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Introduce el <strong>ID del set</strong> de la API de Pokémon TCG para importar
                    o actualizar todas sus cartas. Los precios de compra se calculan
                    automáticamente al 50% del valor de mercado Cardmarket.
                </p>

                <form action="{{ route('admin.singles.sync') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="set_id" class="form-label fw-semibold">ID del Set</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text"
                                   id="set_id"
                                   name="set_id"
                                   class="form-control @error('set_id') is-invalid @enderror"
                                   placeholder="Ej: sv8pt5, sv8, base1"
                                   value="{{ old('set_id') }}"
                                   required>
                            @error('set_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            Consulta todos los IDs disponibles en la
                            <a href="https://docs.pokemontcg.io/" target="_blank" rel="noopener">
                                documentación oficial <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        <i class="bi bi-cloud-download me-2"></i>
                        Lanzar sincronización
                    </button>
                </form>

                {{-- Sets de acceso rápido --}}
                <hr class="my-3">
                <p class="small fw-semibold text-muted mb-2">Sets recientes (acceso rápido):</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['sv8pt5' => 'Destinos de Paldea', 'sv8' => 'Surging Sparks', 'sv7' => 'Stellar Crown', 'sv6pt5' => 'Shrouded Fable', 'sv6' => 'Twilight Masquerade', 'sv3pt5' => '151', 'base1' => 'Base Set'] as $id => $nombre)
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm sync-quick-btn"
                                data-set="{{ $id }}"
                                title="{{ $nombre }}">
                            {{ $id }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Instrucciones de CLI --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom fw-bold">
                <i class="bi bi-terminal me-2"></i>Comando CLI
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">También puedes lanzar la sincronización desde terminal:</p>
                <code class="d-block bg-dark text-success p-3 rounded small">
                    php artisan singles:sync --set=sv8pt5<br>
                    php artisan singles:sync --sets=sv8,sv7,sv6
                </code>
                <p class="text-muted small mt-2 mb-0">
                    La sincronización automática se ejecuta <strong>cada lunes a las 04:00</strong>
                    via el scheduler de Laravel.
                </p>
            </div>
        </div>
    </div>

    {{-- ── Tabla de singles recientes ── --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Últimas sincronizadas (20)
                </span>
                <span class="badge bg-success rounded-pill">{{ $stats['total'] }} totales</span>
            </div>
            <div class="card-body p-0">
                @if($recientes->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        <p class="mb-0">No hay singles en la base de datos todavía.</p>
                        <p class="small">Lanza tu primera sincronización desde el formulario.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Carta</th>
                                    <th>Set / Nº</th>
                                    <th>Rareza</th>
                                    <th class="text-end">Efectivo</th>
                                    <th class="text-end">Saldo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recientes as $single)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($single->image_url)
                                                    <img src="{{ $single->image_url }}"
                                                         alt="{{ $single->name }}"
                                                         style="width:32px;height:auto;border-radius:4px;">
                                                @endif
                                                <span class="fw-semibold">{{ $single->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">
                                            {{ $single->set_name }}<br>
                                            <small>{{ $single->card_number }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $single->rarity ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ $single->precioCashFormateado() }}
                                        </td>
                                        <td class="text-end text-primary">
                                            {{ $single->precioCreditFormateado() }}
                                        </td>
                                        <td class="text-center">
                                            @if($single->is_active)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Activa</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Rellena el input con el ID del set al hacer clic en los botones de acceso rápido
document.querySelectorAll('.sync-quick-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('set_id').value = this.dataset.set;
        document.getElementById('set_id').focus();
    });
});
</script>
@endpush
