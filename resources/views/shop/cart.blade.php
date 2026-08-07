@extends('layouts.app')
@section('title', 'Carrito — Factory Cards')
@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">Tu carrito</h1>
    @if($items->isEmpty())
        <div class="text-center py-5"><i class="bi bi-cart-x fs-1 text-muted"></i><p class="mt-3 text-muted">Tu carrito está vacío.</p><a href="{{ route('shop.catalog') }}" class="btn btn-primary">Ir al catálogo</a></div>
    @else
        <p class="text-muted">{{ $items->count() }} producto(s) — Total: <strong>{{ number_format($total, 2, ',', '.') }} €</strong></p>
        <a href="{{ route('checkout.index') }}" class="btn btn-success">Proceder al pago</a>
    @endif
</div>
@endsection
