@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="text-muted small">Pedidos totales</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_orders']) }}</div>
                    <div class="text-success small">{{ $stats['paid_orders'] }} pagados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-currency-euro"></i></div>
                <div>
                    <div class="text-muted small">Ingresos este mes</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['revenue_month'], 2, ',', '.') }} €</div>
                    <div class="text-muted small">Total: {{ number_format($stats['revenue_total'], 2, ',', '.') }} €</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="text-muted small">Productos activos</div>
                    <div class="fs-4 fw-bold">{{ $stats['active_products'] }}</div>
                    <div class="text-muted small">{{ $stats['total_products'] }} en total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="text-muted small">Stock bajo (&le;5)</div>
                    <div class="fs-4 fw-bold">{{ $stats['low_stock'] }}</div>
                    <div class="text-danger small">{{ $stats['out_of_stock'] }} sin stock</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent orders --}}
    <div class="col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Últimos pedidos</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-medium text-decoration-none">{{ $order->order_number }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td class="fw-semibold">{{ number_format($order->total, 2, ',', '.') }} €</td>
                            <td>
                                <span class="badge bg-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Sin pedidos todavía</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Low stock --}}
    <div class="col-xl-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stock bajo</h6>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-outline-warning">Ver todos</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($low_stock_products as $p)
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <div>
                        <div class="small fw-medium text-truncate" style="max-width:180px">{{ $p->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $p->sku }}</div>
                    </div>
                    <span class="badge {{ $p->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                        {{ $p->stock === 0 ? 'Sin stock' : $p->stock . ' uds' }}
                    </span>
                </li>
                @empty
                <li class="list-group-item text-center text-muted py-4">Sin alertas de stock</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@endsection
