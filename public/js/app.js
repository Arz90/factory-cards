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
