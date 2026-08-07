{{--
    Vista: admin/events/index
    Propósito: Listado de todos los eventos y torneos con acciones CRUD.
--}}
@extends('layouts.admin')
@section('title', 'Eventos y Torneos')

@section('content')

{{-- ── Cabecera ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Eventos y Torneos</h4>
        <p class="text-muted small mb-0">Gestiona los torneos, presentaciones y eventos de la tienda.</p>
    </div>
    <a href="{{ route('admin.eventos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo evento
    </a>
</div>

{{-- ── Tabla de eventos ── --}}
@if($events->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-calendar-event text-muted" style="font-size:3rem;"></i>
        <p class="text-muted mt-3 mb-3">No hay eventos creados todavía.</p>
        <a href="{{ route('admin.eventos.create') }}" class="btn btn-primary btn-sm mx-auto" style="width:fit-content">
            Crear primer evento
        </a>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px">Cartel</th>
                        <th>Título</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Precio</th>
                        <th class="text-center">Activo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        {{-- Miniatura del cartel --}}
                        <td>
                            <img src="{{ $event->urlImagen() }}"
                                 alt="{{ $event->title }}"
                                 class="rounded"
                                 style="width:70px;height:44px;object-fit:cover;">
                        </td>

                        {{-- Título --}}
                        <td class="fw-semibold">{{ $event->title }}</td>

                        {{-- Fecha inicio --}}
                        <td class="text-muted small">{{ $event->start_date->format('d/m/Y H:i') }}</td>

                        {{-- Fecha fin --}}
                        <td class="text-muted small">
                            {{ $event->end_date ? $event->end_date->format('d/m/Y H:i') : '—' }}
                        </td>

                        {{-- Precio --}}
                        <td>
                            <span class="badge {{ $event->esPago() ? 'bg-primary' : 'bg-success' }}">
                                {{ $event->precioFormateado() }}
                            </span>
                        </td>

                        {{-- Estado activo/inactivo --}}
                        <td class="text-center">
                            <span class="badge bg-{{ $event->is_active ? 'success' : 'secondary' }}">
                                {{ $event->is_active ? 'Sí' : 'No' }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="text-end">
                            <a href="{{ route('admin.eventos.edit', $event) }}"
                               class="btn btn-sm btn-outline-secondary me-1"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('admin.eventos.destroy', $event) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-swal-delete"
                                        data-nombre="{{ $event->title }}"
                                        title="Eliminar evento">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($events->hasPages())
            <div class="card-footer bg-white">{{ $events->links() }}</div>
        @endif
    </div>
@endif

@endsection
