@extends('layouts.app')
@section('title', $product->name . ' — Factory Cards')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-md-5">
            @if($product->image_url)
                <img src="{{ asset($product->image_url) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:350px;"><i class="bi bi-image fs-1 text-muted"></i></div>
            @endif
        </div>
        <div class="col-md-7">
            @if($product->franchise)
                <span class="badge mb-2" style="background:{{ $product->franchise->color }}">{{ $product->franchise->name }}</span>
            @endif
            <h1 class="h3 fw-bold">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->short_description }}</p>
            <div class="mb-3">
                <span class="fs-3 fw-bold text-primary">{{ number_format($product->price, 2, ',', '.') }} €</span>
                @if($product->hasDiscount())
                    <del class="text-muted ms-2 fs-5">{{ number_format($product->original_price, 2, ',', '.') }} €</del>
                    <span class="badge bg-danger ms-1">-{{ $product->discountPercentage() }}%</span>
                @endif
            </div>
            @if($product->isAvailable())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:80px">
                    <button class="btn btn-primary"><i class="bi bi-cart-plus me-1"></i> Añadir al carrito</button>
                </form>
                <div class="text-muted small mt-2"><i class="bi bi-check-circle-fill text-success me-1"></i>{{ $product->stock }} en stock</div>
            @else
                <button class="btn btn-secondary" disabled>Sin stock</button>
            @endif
        </div>
    </div>
</div>
@endsection
