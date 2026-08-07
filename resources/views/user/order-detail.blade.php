@extends('layouts.app')
@section('title', 'Pedido ' . $order->order_number)
@section('content')
<div class="container py-4">
    <h1 class="h4 fw-bold mb-1">{{ $order->order_number }}</h1>
    <p class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }} — <span class="badge bg-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span></p>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Producto</th><th>Precio</th><th>Cant.</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->price, 2, ',', '.') }} €</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="fw-bold">{{ number_format($order->total, 2, ',', '.') }} €</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
