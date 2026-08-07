@extends('layouts.admin')
@section('title', 'Productos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Productos</h4>
        <span class="text-muted small">{{ $products->total() }} en total</span>
    </div>
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo producto
    </a>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Buscar por nombre...">
            </div>
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="active"   @selected(request('status')==='active')>Activo</option>
                    <option value="inactive" @selected(request('status')==='inactive')>Inactivo</option>
                    <option value="preorder" @selected(request('status')==='preorder')>Precompra</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select name="franchise_id" class="form-select form-select-sm">
                    <option value="">Todas las franquicias</option>
                    @foreach($franchises as $f)
                    <option value="{{ $f->id }}" @selected(request('franchise_id')==$f->id)>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 d-flex gap-1">
                <button class="btn btn-sm btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-outline-secondary">×</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:56px">Img</th>
                    <th>Nombre / SKU</th>
                    <th>Franquicia</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Dest.</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image_url)
                            <img src="{{ asset($product->image_url) }}" alt="" class="rounded" style="width:44px;height:44px;object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-medium">{{ Str::limit($product->name, 50) }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $product->sku }}</div>
                    </td>
                    <td>
                        @if($product->franchise)
                            <span class="badge rounded-pill" style="background:{{ $product->franchise->color }}20;color:{{ $product->franchise->color }};border:1px solid {{ $product->franchise->color }}40">
                                {{ $product->franchise->name }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ number_format($product->price, 2, ',', '.') }} €</div>
                        @if($product->hasDiscount())
                            <del class="text-muted small">{{ number_format($product->original_price, 2, ',', '.') }} €</del>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <input type="number" min="0" value="{{ $product->stock }}"
                                class="form-control form-control-sm stock-input"
                                style="width:70px"
                                data-id="{{ $product->id }}"
                                data-url="{{ route('admin.productos.stock', $product) }}">
                            <span class="spinner-border spinner-border-sm text-primary d-none stock-spinner" role="status"></span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ match($product->status) { 'active' => 'success', 'preorder' => 'primary', default => 'secondary' } }}">
                            {{ match($product->status) { 'active' => 'Activo', 'preorder' => 'Precompra', default => 'Inactivo' } }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm p-0 featured-btn" data-id="{{ $product->id }}"
                            data-url="{{ route('admin.productos.featured', $product) }}"
                            title="{{ $product->is_featured ? 'Destacado' : 'No destacado' }}">
                            <i class="bi bi-star{{ $product->is_featured ? '-fill text-warning' : ' text-muted' }} fs-5"></i>
                        </button>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.productos.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.productos.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar «{{ addslashes($product->name) }}»?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No hay productos. <a href="{{ route('admin.productos.create') }}">Crea el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-white">
        {{ $products->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.stock-input').forEach(input => {
    let timer;
    input.addEventListener('change', function () {
        const spinner = this.closest('td').querySelector('.stock-spinner');
        spinner.classList.remove('d-none');
        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch(this.dataset.url, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ stock: this.value })
            }).finally(() => spinner.classList.add('d-none'));
        }, 600);
    });
});

document.querySelectorAll('.featured-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        fetch(this.dataset.url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        })
        .then(r => r.json())
        .then(d => {
            const icon = this.querySelector('i');
            icon.className = d.is_featured ? 'bi bi-star-fill text-warning fs-5' : 'bi bi-star text-muted fs-5';
            this.title = d.is_featured ? 'Destacado' : 'No destacado';
        });
    });
});
</script>
@endpush
