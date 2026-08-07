@extends('layouts.admin')
@section('title', 'Pedidos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Pedidos</h4>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nº pedido, email, nombre..."></div>
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    @foreach(['pending'=>'Pendiente','paid'=>'Pagado','processing'=>'En proceso','shipped'=>'Enviado','delivered'=>'Entregado','cancelled'=>'Cancelado'] as $v => $l)
                    <option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 d-flex gap-1">
                <button class="btn btn-sm btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">×</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nº Pedido</th><th>Cliente</th><th>Total</th><th>Método pago</th><th>Estado</th><th>Fecha</th><th></th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-medium text-decoration-none">{{ $order->order_number }}</a></td>
                    <td>
                        <div>{{ $order->customer_name }}</div>
                        <div class="text-muted small">{{ $order->customer_email }}</div>
                    </td>
                    <td class="fw-semibold">{{ number_format($order->total, 2, ',', '.') }} €</td>
                    <td class="text-muted small text-capitalize">{{ $order->payment_method ?? '—' }}</td>
                    <td><span class="badge bg-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span></td>
                    <td class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">Sin pedidos todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())<div class="card-footer bg-white">{{ $orders->links() }}</div>@endif
</div>
@endsection
