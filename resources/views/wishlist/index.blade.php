{{--
    Vista: wishlist/index
    Propósito: Lista de productos guardados en favoritos por el usuario.
--}}
@extends('layouts.app')

@section('title', 'Mi Lista de Deseos — Factory Cards')

@section('content')
<div class="container py-5">

    {{-- Cabecera --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold mb-0">
            <i class="bi bi-heart-fill me-2" style="color:#EF4444"></i>
            Mi Lista de Deseos
        </h1>
        {{-- El contador se actualiza dinámicamente desde JS --}}
        <span id="wishlist-contador" class="text-muted small {{ $productos->isEmpty() ? 'd-none' : '' }}">
            {{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}
        </span>
    </div>

    {{-- Estado vacío: oculto si hay productos, JS lo muestra cuando se quitan todos --}}
    <div id="wishlist-vacio" class="text-center py-5 {{ $productos->isNotEmpty() ? 'd-none' : '' }}">
        <i class="bi bi-heart text-muted" style="font-size:4rem;opacity:.3"></i>
        <p class="text-muted mt-3 mb-4">Aún no has guardado ningún producto en favoritos.</p>
        <a href="{{ route('shop.catalog') }}" class="btn btn-success">
            <i class="bi bi-shop me-2"></i>Explorar la tienda
        </a>
    </div>

    {{-- Grid de productos — JS elimina columnas dinámicamente al quitar favoritos --}}
    <div id="wishlist-grid" class="row g-3 {{ $productos->isEmpty() ? 'd-none' : '' }}">
        @foreach($productos as $product)
        <div class="col-6 col-sm-4 col-lg-3 col-xl-2-4">
            @include('partials.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>

</div>
@endsection
