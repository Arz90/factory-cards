@extends('layouts.app')
@section('title', 'Pedido confirmado — Factory Cards')
@section('content')
<div class="container py-5 text-center">
    <i class="bi bi-check-circle-fill fs-1 text-success"></i>
    <h1 class="h3 fw-bold mt-3">¡Pedido confirmado!</h1>
    <p class="text-muted">Nº {{ $order->order_number }}</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Volver a la tienda</a>
</div>
@endsection
