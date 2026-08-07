@extends('layouts.admin')
@section('title', 'Franquicias')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Franquicias</h4>
    <a href="{{ route('admin.franquicias.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva</a>
</div>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Color</th><th>Nombre</th><th>Slug</th><th>Orden</th><th>Activa</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($franchises as $f)
                <tr>
                    <td><span class="rounded-circle d-inline-block" style="width:20px;height:20px;background:{{ $f->color ?? '#ccc' }}"></span></td>
                    <td class="fw-medium">{{ $f->name }}</td>
                    <td class="text-muted small">{{ $f->slug }}</td>
                    <td>{{ $f->sort_order }}</td>
                    <td><span class="badge bg-{{ $f->is_active ? 'success' : 'secondary' }}">{{ $f->is_active ? 'Sí' : 'No' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.franquicias.edit', $f) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.franquicias.destroy', $f) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Sin franquicias.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
