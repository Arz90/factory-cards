@extends('layouts.admin')
@section('title', 'Categorías')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Categorías</h4>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nueva</a>
</div>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nombre</th><th>Padre</th><th>Orden</th><th>Activa</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td class="fw-medium">{{ $cat->name }}</td>
                    <td>{{ $cat->parent?->name ?? '—' }}</td>
                    <td>{{ $cat->sort_order }}</td>
                    <td><span class="badge bg-{{ $cat->is_active ? 'success' : 'secondary' }}">{{ $cat->is_active ? 'Sí' : 'No' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.categorias.edit', $cat) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.categorias.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Sin categorías.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())<div class="card-footer bg-white">{{ $categories->links() }}</div>@endif
</div>
@endsection
