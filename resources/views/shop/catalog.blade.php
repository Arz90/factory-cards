{{--
    Vista: shop/catalog
    Propósito: Listado paginado del catálogo completo.
    También lo usan: shop.franchise, shop.category, shop.search
--}}
@extends('layouts.app')
@section('title', 'Catálogo — Factory Cards')

@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">Catálogo</h1>

    <div class="row g-3">
        @forelse($products as $product)
        <div class="col-6 col-sm-4 col-lg-3 col-xl-2-4">
            @include('partials.product-card', ['product' => $product])
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">No hay productos disponibles.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
