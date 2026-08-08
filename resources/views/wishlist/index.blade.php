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
        @if($productos->count())
            <span class="text-muted small">{{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}</span>
        @endif
    </div>

    @if($productos->isEmpty())
        {{-- Estado vacío --}}
        <div class="text-center py-5">
            <i class="bi bi-heart text-muted" style="font-size:4rem;opacity:.3"></i>
            <p class="text-muted mt-3 mb-4">Aún no has guardado ningún producto en favoritos.</p>
            <a href="{{ route('shop.catalog') }}" class="btn btn-success">
                <i class="bi bi-shop me-2"></i>Explorar la tienda
            </a>
        </div>
    @else
        {{-- Grid de productos usando el partial reutilizable --}}
        <div class="row g-3">
            @foreach($productos as $product)
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2-4">
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
