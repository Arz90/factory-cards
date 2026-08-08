/**
 * Factory Cards — JS principal
 * Vanilla JS + Bootstrap 5 (sin jQuery)
 */

'use strict';

// ── Cabecera sticky: ocultar topbar al hacer scroll ──────────────────────
(function () {
    const topbar = document.querySelector('.topbar');
    if (!topbar) return;
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const current = window.scrollY;
        topbar.style.display = current > 60 ? 'none' : '';
        lastScroll = current;
    }, { passive: true });
})();

// ── Añadir al carrito via AJAX ────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-add-to-cart]');
    if (!btn) return;
    e.preventDefault();

    const productId = btn.dataset.addToCart;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(`/carrito/anadir/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ quantity: 1 }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Actualizar contador del carrito
            document.querySelectorAll('.cart-badge').forEach(badge => {
                badge.textContent = data.cart_count;
                badge.classList.remove('d-none');
            });
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Añadido';
            btn.classList.replace('btn-warning', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.replace('btn-success', 'btn-warning');
                btn.disabled = false;
            }, 2000);
        } else {
            showToast(data.message || 'Error al añadir al carrito', 'danger');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Error de conexión. Inténtalo de nuevo.', 'danger');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
});

// ── Toast de notificaciones ───────────────────────────────────────────────
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container') || createToastContainer();
    const id = 'toast-' + Date.now();
    const html = `
        <div id="${id}" class="toast align-items-center text-bg-${type} border-0 shadow" role="alert" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-semibold">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    const toastEl = document.getElementById(id);
    const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

function createToastContainer() {
    const div = document.createElement('div');
    div.id = 'toast-container';
    div.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    div.style.zIndex = 9999;
    document.body.appendChild(div);
    return div;
}

// Exportar para uso global
window.showToast = showToast;

// ── Tarjeta de producto (pc-card): Añadir al carrito ─────────────────────
// Maneja el botón [data-pc-cart] del overlay de las tarjetas de la portada y catálogo.
document.addEventListener('click', function (e) {
    const boton = e.target.closest('[data-pc-cart]');
    if (!boton) return;
    e.preventDefault();

    if (boton.dataset.cargando === 'true') return;
    boton.dataset.cargando = 'true';

    const productId = boton.dataset.pcCart;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const icon = boton.querySelector('i');
    const span = boton.querySelector('span');

    icon.className  = 'bi bi-hourglass-split';
    span.textContent = '…';

    fetch(`/carrito/anadir/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ quantity: 1 }),
    })
    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(data => {
        // Actualizar contador del carrito en la navbar
        document.querySelectorAll('.cart-badge').forEach(badge => {
            badge.textContent = data.cart_count ?? '';
            badge.classList.remove('d-none');
        });
        icon.className   = 'bi bi-check-lg';
        span.textContent = '¡Añadido!';
        boton.classList.add('pc-overlay-btn--ok');
        showToast('Producto añadido al carrito', 'success');
    })
    .catch(err => {
        console.error('[pc-cart] Error al añadir al carrito:', err);
        icon.className   = 'bi bi-exclamation-triangle';
        span.textContent = 'Error';
        showToast('No se pudo añadir el producto', 'danger');
    })
    .finally(() => {
        setTimeout(() => {
            icon.className          = 'bi bi-cart-plus';
            span.textContent        = 'Añadir';
            boton.dataset.cargando  = 'false';
            boton.classList.remove('pc-overlay-btn--ok');
        }, 2000);
    });
});

// ── Tarjeta de producto (pc-card): Toggle Wishlist ────────────────────────
// Maneja el botón [data-pc-wishlist] — requiere autenticación.
document.addEventListener('click', function (e) {
    const boton = e.target.closest('[data-pc-wishlist]');
    if (!boton) return;
    e.preventDefault();

    // Si el usuario no está autenticado, redirigir al login
    const autenticado = document.querySelector('meta[name="user-autenticado"]')?.content === '1';
    if (!autenticado) {
        showToast('Inicia sesión para guardar favoritos', 'warning');
        setTimeout(() => { window.location.href = '/login'; }, 1500);
        return;
    }

    if (boton.dataset.cargando === 'true') return;
    boton.dataset.cargando = 'true';

    const productId  = boton.dataset.pcWishlist;
    const estaEnWish = boton.dataset.pcWishlisted === '1';
    const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;
    const icon       = boton.querySelector('i');

    fetch(`/deseos/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(data => {
        const añadido = data.añadido;

        // Actualizar icono y estado visual
        icon.className = añadido ? 'bi bi-heart-fill' : 'bi bi-heart';
        boton.dataset.pcWishlisted = añadido ? '1' : '0';
        boton.title = añadido ? 'Quitar de favoritos' : 'Añadir a favoritos';

        if (añadido) {
            boton.classList.add('pc-overlay-btn--fav-activo');
        } else {
            boton.classList.remove('pc-overlay-btn--fav-activo');
        }

        showToast(data.mensaje ?? (añadido ? 'Añadido a favoritos' : 'Eliminado de favoritos'), 'success');

        // ── Si estamos en la página de wishlist y se ha eliminado, animar y quitar la tarjeta ──
        if (!añadido) {
            const grid = document.getElementById('wishlist-grid');
            if (!grid) return; // no estamos en la página wishlist

            // Buscar la columna contenedora de esta tarjeta
            const columna = boton.closest('[class*="col-"]');
            if (!columna) return;

            // Aplicar animación pop-out y eliminar del DOM al terminar
            columna.classList.add('wishlist-salida');
            columna.addEventListener('animationend', function () {
                columna.remove();

                // Actualizar contador
                const restantes = grid.querySelectorAll('[class*="col-"]').length;
                const contador  = document.getElementById('wishlist-contador');
                if (contador) {
                    if (restantes > 0) {
                        contador.textContent = restantes + ' ' + (restantes === 1 ? 'producto' : 'productos');
                    } else {
                        contador.classList.add('d-none');
                    }
                }

                // Si no quedan productos, mostrar estado vacío y ocultar grid
                if (restantes === 0) {
                    grid.classList.add('d-none');
                    const vacio = document.getElementById('wishlist-vacio');
                    if (vacio) vacio.classList.remove('d-none');
                }
            }, { once: true });
        }
    })
    .catch(err => {
        console.error('[pc-wishlist] Error al actualizar wishlist:', err);
        showToast('No se pudo actualizar la lista de deseos', 'danger');
    })
    .finally(() => {
        boton.dataset.cargando = 'false';
    });
});
