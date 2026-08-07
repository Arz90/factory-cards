@extends('layouts.app')
@section('title', 'Factory Cards — Tienda TCG')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-1">Bienvenido a Factory Cards</h1>
    <p class="text-muted mb-4">Tu tienda especializada en TCG — Pokémon, Magic, One Piece y más.</p>

    @if($featured->count())
    <h2 class="h5 fw-semibold mb-3">Productos destacados</h2>
    <div class="row g-3 mb-5">
        @foreach($featured as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    @if($product->image_url)
                        <img src="{{ asset($product->image_url) }}" class="card-img-top" style="height:180px;object-fit:cover" alt="">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;"><i class="bi bi-image fs-1 text-muted"></i></div>
                    @endif
                    <div class="card-body p-2">
                        <div class="small fw-semibold text-dark">{{ Str::limit($product->name, 60) }}</div>
                        <div class="mt-1">
                            <span class="fw-bold text-primary">{{ number_format($product->price, 2, ',', '.') }} €</span>
                            @if($product->hasDiscount())
                                <del class="text-muted small ms-1">{{ number_format($product->original_price, 2, ',', '.') }} €</del>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('shop.catalog') }}" class="btn btn-primary btn-lg">Ver catálogo completo</a>
    </div>
</div>
@endsection
