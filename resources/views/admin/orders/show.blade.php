@extends('layouts.admin')
@section('title', 'Pedido ' . $order->order_number)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ $order->order_number }}</h4>
        <span class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Líneas del pedido</h6></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Producto</th><th>Precio</th><th>Cant.</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->price, 2, ',', '.') }} €</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end fw-semibold">{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end">{{ number_format($order->subtotal, 2, ',', '.') }} €</td></tr>
                        <tr><td colspan="3" class="text-end">Envío</td><td class="text-end">{{ number_format($order->shipping_cost, 2, ',', '.') }} €</td></tr>
                        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold fs-5">{{ number_format($order->total, 2, ',', '.') }} €</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Estado del pedido</h6></div>
            <div class="card-body">
                <div class="mb-2"><span class="badge bg-{{ $order->statusColor() }} fs-6">{{ $order->statusLabel() }}</span></div>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm mb-2">
                        @foreach(['pending'=>'Pendiente','paid'=>'Pagado','processing'=>'En proceso','shipped'=>'Enviado','delivered'=>'Entregado','cancelled'=>'Cancelado','refunded'=>'Reembolsado'] as $v => $l)
                        <option value="{{ $v }}" @selected($order->status===$v)>{{ $l }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary w-100">Actualizar estado</button>
                </form>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0 fw-semibold">Cliente</h6></div>
            <div class="card-body small">
                <div class="fw-semibold">{{ $order->customer_name }}</div>
                <div>{{ $order->customer_email }}</div>
                <div>{{ $order->customer_phone }}</div>
                <hr>
                <div class="fw-semibold">Envío</div>
                <div>{{ $order->shipping_address }}</div>
                <div>{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</div>
                <div>{{ $order->shipping_country }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
