@extends('layouts.app')
@section('title', 'Catálogo — Factory Cards')

@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">Catálogo</h1>
    <div class="row g-3">
        @forelse($products as $product)
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
                        <div class="mt-1"><span class="fw-bold text-primary">{{ number_format($product->price, 2, ',', '.') }} €</span></div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">No hay productos disponibles.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
