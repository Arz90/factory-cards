@extends('layouts.app')
@section('title', 'Carrito de compra — Factory Cards')

@section('content')
<div class="container py-4">

    {{-- ── Encabezado ── --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <h1 class="h3 fw-bold mb-0">Carrito de compra</h1>
        @if(!$items->isEmpty())
            <span class="badge bg-secondary rounded-pill">
                {{ $items->count() }} {{ $items->count() === 1 ? 'artículo' : 'artículos' }}
            </span>
        @endif
    </div>

    {{-- ── Carrito vacío ── --}}
    @if($items->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:4rem;color:#ccc;"></i>
            <h4 class="mt-3 fw-bold">Tu carrito está vacío</h4>
            <p class="text-muted mb-4">Añade productos desde el catálogo para empezar a comprar.</p>
            <a href="{{ route('shop.catalog') }}" class="btn btn-primary px-4">
                <i class="bi bi-shop me-2"></i>Ir al catálogo
            </a>
        </div>

    {{-- ── Carrito con productos ── --}}
    @else
        <div class="row g-4 align-items-start">

            {{-- ════════════════════════
                 Lista de productos
            ════════════════════════ --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">

                        @if(auth()->check())

                            {{-- Items de usuario autenticado --}}
                            @foreach($items as $item)
                                <div class="cart-item d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                                     id="cart-item-{{ $item->id }}">

                                    {{-- Imagen --}}
                                    <a href="{{ route('shop.product', $item->product->slug) }}" class="flex-shrink-0">
                                        @if($item->product->image_url)
                                            <img src="{{ asset($item->product->image_url) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="cart-item-img rounded">
                                        @else
                                            <div class="cart-item-img rounded bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted fs-3"></i>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Info + controles --}}
                                    <div class="flex-grow-1 d-flex flex-column justify-content-between min-w-0">

                                        <div>
                                            <a href="{{ route('shop.product', $item->product->slug) }}"
                                               class="fw-bold text-dark text-decoration-none cart-product-name d-block">
                                                {{ $item->product->name }}
                                            </a>
                                            @if($item->product->sku)
                                                <div class="text-muted small mt-1">SKU: {{ $item->product->sku }}</div>
                                            @endif
                                            @if($item->product->franchise)
                                                <span class="badge rounded-pill small mt-1"
                                                      style="background:{{ $item->product->franchise->color }}20;color:{{ $item->product->franchise->color }};border:1px solid {{ $item->product->franchise->color }}40">
                                                    {{ $item->product->franchise->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">

                                            {{-- Control de cantidad --}}
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="input-group input-group-sm cart-qty-group">
                                                    <button class="btn btn-outline-secondary cart-qty-btn"
                                                            type="button"
                                                            data-action="decrement"
                                                            data-item-id="{{ $item->id }}"
                                                            data-unit-price="{{ $item->product->price }}"
                                                            data-url="{{ route('cart.update', $item) }}"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number"
                                                           class="form-control text-center cart-qty-input"
                                                           value="{{ $item->quantity }}"
                                                           min="1" max="99"
                                                           data-item-id="{{ $item->id }}"
                                                           readonly>
                                                    <button class="btn btn-outline-secondary cart-qty-btn"
                                                            type="button"
                                                            data-action="increment"
                                                            data-item-id="{{ $item->id }}"
                                                            data-unit-price="{{ $item->product->price }}"
                                                            data-url="{{ route('cart.update', $item) }}"
                                                            {{ $item->quantity >= 99 ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                                <span class="cart-qty-spinner spinner-border spinner-border-sm text-primary d-none" role="status"></span>
                                            </div>

                                            {{-- Precio línea + eliminar --}}
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="text-end">
                                                    <div class="fw-bold fs-6 cart-line-total" data-item-id="{{ $item->id }}">
                                                        {{ number_format($item->lineTotal(), 2, ',', '.') }} €
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ number_format($item->product->price, 2, ',', '.') }} € / ud.
                                                    </div>
                                                </div>
                                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Eliminar del carrito">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else

                            {{-- Items de guest (arrays de sesión) --}}
                            @foreach($items as $item)
                                <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    @if($item['image_url'] ?? null)
                                        <img src="{{ asset($item['image_url']) }}"
                                             alt="{{ $item['name'] }}"
                                             class="cart-item-img rounded">
                                    @else
                                        <div class="cart-item-img rounded bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted fs-3"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 d-flex flex-column justify-content-between">
                                        <div class="fw-bold">{{ $item['name'] }}</div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="text-muted small">Cantidad: {{ $item['quantity'] }}</span>
                                            <div class="fw-bold">
                                                {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2, ',', '.') }} €
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="p-3 bg-light border-top text-center small">
                                <i class="bi bi-person-circle me-1 text-muted"></i>
                                <a href="{{ route('login') }}" class="fw-semibold">Inicia sesión</a>
                                para modificar cantidades y guardar tu carrito.
                            </div>

                        @endif

                    </div>

                    {{-- Footer: vaciar carrito + seguir comprando --}}
                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('shop.catalog') }}" class="text-muted text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i>Seguir comprando
                        </a>
                        @if(auth()->check())
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-trash3 me-1"></i>Vaciar carrito
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ════════════════════════
                 Resumen del pedido
            ════════════════════════ --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="position:sticky;top:80px;">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        Resumen del pedido
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">
                                Subtotal ({{ $items->count() }} {{ $items->count() === 1 ? 'artículo' : 'artículos' }})
                            </span>
                            <span class="fw-semibold" id="resumen-subtotal">
                                {{ number_format($total, 2, ',', '.') }} €
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Envío</span>
                            <span class="text-muted small">Calculado en el pago</span>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-success" id="resumen-total">
                                {{ number_format($total, 2, ',', '.') }} €
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 py-2 fw-bold mb-3">
                            <i class="bi bi-lock-fill me-2"></i>Proceder al pago
                        </a>

                        <div class="text-center">
                            <div class="text-muted small mb-2">Pago 100% seguro</div>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <span class="badge bg-light text-dark border small">
                                    <i class="bi bi-credit-card me-1"></i>Tarjeta
                                </span>
                                <span class="badge bg-light text-dark border small">
                                    <i class="bi bi-bank me-1"></i>TPV Virtual
                                </span>
                                <span class="badge bg-light text-dark border small">
                                    <i class="bi bi-shield-check text-success me-1"></i>3D Secure
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.cart-item-img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 6px;
    flex-shrink: 0;
}
.cart-qty-group  { width: 110px; }
.cart-qty-input  { font-size: .9rem; background: #fff !important; }
.cart-product-name { font-size: .95rem; line-height: 1.3; }
.cart-item       { transition: background .15s; }
.cart-item:hover { background: #fafafa; }
@media (max-width: 575px) {
    .cart-item-img { width: 72px; height: 72px; }
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Botones +/− del carrito: actualiza la cantidad mediante AJAX (PATCH)
 * y recalcula el total de línea y el resumen sin recargar la página.
 */
document.querySelectorAll('.cart-qty-btn').forEach(function(btn) {
    btn.addEventListener('click', async function() {
        const itemId    = this.dataset.itemId;
        const url       = this.dataset.url;
        const accion    = this.dataset.action;
        const unitPrice = parseFloat(this.dataset.unitPrice) || 0;
        const row       = document.getElementById('cart-item-' + itemId);
        const input     = row.querySelector('.cart-qty-input');
        const spinner   = row.querySelector('.cart-qty-spinner');

        let qty = parseInt(input.value);
        qty = accion === 'increment' ? qty + 1 : qty - 1;
        if (qty < 1 || qty > 99) return;

        // Deshabilitar controles mientras se envía
        row.querySelectorAll('.cart-qty-btn').forEach(b => b.disabled = true);
        spinner.classList.remove('d-none');

        try {
            const resp = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: qty }),
            });

            if (!resp.ok) throw new Error('Error ' + resp.status);

            // Actualizar input y total de línea
            input.value = qty;
            const lineTotal = qty * unitPrice;
            row.querySelector('.cart-line-total').textContent =
                lineTotal.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

            // Recalcular resumen sumando todos los totales de línea
            recalcularResumen();

            // Actualizar estado de botones −/+
            row.querySelector('[data-action="decrement"]').disabled = (qty <= 1);
            row.querySelector('[data-action="increment"]').disabled = (qty >= 99);

        } catch (err) {
            console.error('Error al actualizar cantidad:', err);
            alert('No se pudo actualizar la cantidad. Inténtalo de nuevo.');
        } finally {
            spinner.classList.add('d-none');
        }
    });
});

/**
 * Suma todos los totales de línea visibles y actualiza el resumen lateral.
 */
function recalcularResumen() {
    let subtotal = 0;
    document.querySelectorAll('.cart-line-total').forEach(function(el) {
        const valor = el.textContent.trim().replace(/\./g, '').replace(',', '.').replace(' €', '');
        subtotal += parseFloat(valor) || 0;
    });
    const fmt = subtotal.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    document.getElementById('resumen-subtotal').textContent = fmt;
    document.getElementById('resumen-total').textContent    = fmt;
}
</script>
@endpush
