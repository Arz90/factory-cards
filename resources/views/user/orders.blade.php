@extends('layouts.app')
@section('title', 'Mis pedidos — Factory Cards')
@section('content')
<div class="container py-4">
    <h1 class="h4 fw-bold mb-4">Mis pedidos</h1>
    @forelse($orders as $order)
    <div class="card mb-2 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">{{ $order->order_number }}</div>
                <div class="text-muted small">{{ $order->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="text-end">
                <div class="fw-bold">{{ number_format($order->total, 2, ',', '.') }} €</div>
                <span class="badge bg-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
            </div>
        </div>
    </div>
    @empty
    <p class="text-muted">Aún no tienes pedidos.</p>
    @endforelse
    {{ $orders->links() }}
</div>
@endsection
