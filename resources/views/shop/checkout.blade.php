@extends('layouts.app')
@section('title', 'Checkout — Factory Cards')
@section('content')
<div class="container py-5 text-center">
    <i class="bi bi-credit-card fs-1 text-primary"></i>
    <h1 class="h3 fw-bold mt-3">Pasarela de pago</h1>
    <p class="text-muted">En construcción — Fase 3.</p>
    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">Volver al carrito</a>
</div>
@endsection
