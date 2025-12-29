// Lightweight cart helper used by Blade views.
// This file was refactored to reduce repetition and improve DOM updates.

// --- Network helpers ----------------------------------------------------
function getCsrfToken() {
    const tokenEl = document.querySelector('meta[name="csrf-token"]');
    return tokenEl ? tokenEl.getAttribute('content') : null;
}

async function fetchJson(url, body = {}) {
    if (!url) throw new Error('fetchJson: url is required');
    const token = getCsrfToken();
    const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
    if (token) headers['X-CSRF-TOKEN'] = token;
    const res = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers,
        body: JSON.stringify(body)
    });
    if (!res.ok) {
        const txt = await res.text().catch(() => '');
        const err = new Error('Request failed: ' + res.status + '\n' + txt);
        err.status = res.status;
        throw err;
    }
    return await res.json();
}

export async function cartAdd(opts) {
    return await fetchJson(opts.url, { product_id: opts.product_id, quantity: opts.quantity });
}

export async function cartUpdate(opts) {
    return await fetchJson(opts.url, { product_id: opts.product_id, quantity: opts.quantity });
}

export async function cartRemove(opts) {
    return await fetchJson(opts.url, { product_id: opts.product_id });
}

// keep globals for existing inline handlers in Blade
window.cartAdd = cartAdd;
window.cartUpdate = cartUpdate;
window.cartRemove = cartRemove;

// --- Utilities ----------------------------------------------------------
function parseCurrency(text) {
    if (!text) return 0;
    return parseInt(String(text).replace(/[^0-9]/g, ''), 10) || 0;
}

function formatCurrency(val) {
    return (val || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' đ';
}

// --- DOM snapshot / rendering ------------------------------------------
function getCartDomSnapshot() {
    const items = {};
    const rows = document.querySelectorAll('#cart-items [data-product-id]');
    rows.forEach(li => {
        const id = li.getAttribute('data-product-id');
        const qtyEl = li.querySelector('[data-cart-qty]');
        const lineEl = li.querySelector('[data-cart-line-total]');
        const price = parseInt(li.getAttribute('data-price') || '0', 10) || 0;
        const qty = qtyEl ? parseInt(qtyEl.textContent || '0', 10) : 0;
        const line = lineEl ? parseCurrency(lineEl.textContent) : price * qty;
        items[id] = { qty, line, price };
    });
    const subtotalEl = document.querySelector('[data-cart-subtotal]');
    const subtotal = subtotalEl ? parseCurrency(subtotalEl.textContent) : 0;
    return { items, subtotal };
}

function applySnapshot(snapshot) {
    // Efficient update: map existing rows once, then update/create/remove in batch
    const container = document.querySelector('#cart-items');
    if (!container) return;

    const existingNodes = Array.from(container.querySelectorAll('[data-product-id]'));
    const existingMap = new Map();
    existingNodes.forEach(node => existingMap.set(node.getAttribute('data-product-id'), node));

    const toCreate = [];
    const toUpdate = [];

    Object.keys(snapshot.items).forEach(id => {
        const info = snapshot.items[id];
        const li = existingMap.get(id);
        if (li) {
            toUpdate.push({ li, info });
            existingMap.delete(id);
        } else {
            toCreate.push({ id, info });
        }
    });

    // Batch DOM writes inside requestAnimationFrame
    requestAnimationFrame(() => {
        // Update existing rows
        toUpdate.forEach(({ li, info }) => {
            const qtyEl = li.querySelector('[data-cart-qty]');
            const lineEl = li.querySelector('[data-cart-line-total]');
            if (qtyEl && qtyEl.textContent !== String(info.qty)) qtyEl.textContent = String(info.qty);
            const formatted = formatCurrency(info.line);
            if (lineEl && lineEl.textContent !== formatted) lineEl.textContent = formatted;
        });

        // Create missing rows with minimal required structure
        toCreate.forEach(({ id, info }) => {
            const newRow = document.createElement('div');
            newRow.setAttribute('data-product-id', id);
            newRow.setAttribute('data-price', String(info.price || 0));
            newRow.className = 'py-3 border-b';
            newRow.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-medium">Sản phẩm</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span data-cart-qty>${info.qty}</span>
                        <span data-cart-line-total>${formatCurrency(info.line)}</span>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
        });

        // Remove nodes not present in snapshot
        existingMap.forEach(node => node.remove());

        // Update subtotal/total once
        const subtotalEl = document.querySelector('[data-cart-subtotal]'); if (subtotalEl) subtotalEl.textContent = formatCurrency(snapshot.subtotal);
        const totalEl = document.querySelector('[data-cart-total]'); if (totalEl) totalEl.textContent = formatCurrency(snapshot.subtotal);
    });
}

function renderFromPayload(cartArray) {
    const map = {};
    if (!cartArray) { applySnapshot({ items: {}, subtotal: 0 }); return; }
    if (Array.isArray(cartArray)) {
        cartArray.forEach(it => { if (it && it.id != null) map[String(it.id)] = it; });
    } else if (typeof cartArray === 'object') {
        Object.keys(cartArray).forEach(k => { map[String(k)] = cartArray[k]; });
    }
    const snapshot = { items: {}, subtotal: 0 };
    for (const id of Object.keys(map)) {
        const it = map[id];
        const qty = parseInt(it.qty || it.quantity || 0, 10) || 0;
        const price = parseInt(it.price || 0, 10) || 0;
        const line = parseInt(it.total || (price * qty), 10) || (price * qty);
        snapshot.items[id] = { qty, line, price };
        snapshot.subtotal += line;
    }
    applySnapshot(snapshot);
}

function showEmptyCartView() {
    const container = document.querySelector('#cart-items');
    if (container) container.innerHTML = '<div class="p-6 text-center text-gray-600">Giỏ hàng trống.</div>';
    const summary = document.querySelector('#cart-summary');
    if (summary) summary.innerHTML = '<div class="rounded-lg bg-white p-8 shadow text-center"><p class="text-gray-600 mb-4">Giỏ hàng trống.</p><a href="/" class="inline-block rounded-md border border-main-red text-main-red px-4 py-2">Tiếp tục mua sắm</a></div>';
}

// --- badge helper ------------------------------------------------------
function setBadges(count) {
    document.querySelectorAll('[data-cart-badge]').forEach(b => {
        if (!b) return;
        if ((count || 0) > 0) {
            b.textContent = String(count);
            b.style.display = '';
        } else {
            b.style.display = 'none';
        }
    });
}

// LocalStorage sync: listen for updates from other tabs
window.addEventListener('storage', e => {
    if (e.key !== 'cart-updated') return;
    try {
        const payload = JSON.parse(e.newValue || '{}');
        if (payload && payload.cart) {
            renderFromPayload(payload.cart);
            setBadges(payload.count || 0);
            try { syncCheckoutFromPayload(payload.cart); } catch (e) {}
            return;
        }
        // fallback: fetch server state
        fetch('/cart/json', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                if (data && data.success) {
                    renderFromPayload(data.cart);
                    setBadges(data.count || 0);
                    try { syncCheckoutFromPayload(data.cart); } catch (e) {}
                }
            }).catch(() => {});
    } catch (err) {
        // ignore parse errors
    }
});

// --- reconcile helper used by update/remove flows -----------------------
function reconcileServerData(data, successMsg) {
    if (!data) return;
    if (data.cart) renderFromPayload(data.cart);
    if ((data.count || 0) === 0) showEmptyCartView();
    setBadges(data.count || 0);
    try { syncCheckoutFromPayload(data.cart); } catch (e) {}
    try { broadcastCartUpdate(data); } catch (e) {}
    if (successMsg && window.showToast) window.showToast(successMsg);
    // refresh navbar dropdown preview if present
    try { refreshCartPreviewHtml(data); } catch (e) {}
}

// Try to refresh any cart preview HTML (navbar) so dropdown stays in sync.
async function refreshCartPreviewHtml(data) {
    try {
        const el = document.querySelector('[x-html]');
        if (!el) return;
        if (data && data.html) {
            el.innerHTML = data.html;
            return;
        }
        const res = await fetch('/cart/preview', { credentials: 'same-origin' });
        if (!res.ok) return;
        const text = await res.text();
        el.innerHTML = text;
    } catch (e) {
        // ignore
    }
}

// Debounced broadcaster for localStorage so rapid actions don't spam storage events
let __cart_broadcast_timer = null;
function broadcastCartUpdate(data) {
    try {
        if (__cart_broadcast_timer) clearTimeout(__cart_broadcast_timer);
        __cart_broadcast_timer = setTimeout(() => {
            try { localStorage.setItem('cart-updated', JSON.stringify({ ts: Date.now(), cart: data.cart, count: data.count })); } catch (e) {}
            __cart_broadcast_timer = null;
        }, 50);
    } catch (e) {}
}

// --- actions (perform optimistic updates) ------------------------------
export async function cartPerformUpdate(opts) {
    const snapshot = getCartDomSnapshot();
    try {
        const id = String(opts.product_id);
        const li = document.querySelector('#cart-items [data-product-id="' + id + '"]');
        if (li) {
            const price = parseInt(li.getAttribute('data-price') || '0', 10) || 0;
            const qtyEl = li.querySelector('[data-cart-qty]');
            const newQty = parseInt(opts.quantity || 0, 10) || 0;
            if (newQty <= 0) {
                li.remove();
            } else {
                if (qtyEl) qtyEl.textContent = String(newQty);
                const lineEl = li.querySelector('[data-cart-line-total]');
                if (lineEl) lineEl.textContent = formatCurrency(price * newQty);
            }
            // update subtotal quickly from DOM
            let newSubtotal = 0;
            document.querySelectorAll('#cart-items [data-cart-line-total]').forEach(el => newSubtotal += parseCurrency(el.textContent));
            const subtotalEl = document.querySelector('[data-cart-subtotal]'); if (subtotalEl) subtotalEl.textContent = formatCurrency(newSubtotal);
            const totalEl = document.querySelector('[data-cart-total]'); if (totalEl) totalEl.textContent = formatCurrency(newSubtotal);
        }

        const data = await cartUpdate(opts);
        if (data && data.success) reconcileServerData(data, 'Cập nhật giỏ hàng');
        return data;
    } catch (err) {
        // revert optimistic
        try { applySnapshot(snapshot); } catch (e) {}
        console.error('cartPerformUpdate error', err);
        if (window.showToast) window.showToast('Lỗi khi cập nhật giỏ hàng', { type: 'error' });
        throw err;
    }
}

export async function cartPerformRemove(opts) {
    const snapshot = getCartDomSnapshot();
    try {
        const id = String(opts.product_id);
        const li = document.querySelector('#cart-items [data-product-id="' + id + '"]');
        if (li) {
            // animate collapse
            li.style.transition = 'opacity 220ms ease, height 220ms ease, margin 220ms ease, padding 220ms ease';
            li.style.opacity = '0';
            li.style.padding = '0';
            li.style.margin = '0';
            try {
                const height = li.offsetHeight + 'px';
                li.style.height = height;
                requestAnimationFrame(() => { li.style.height = '0px'; });
            } catch (e) {}
            setTimeout(() => { try { li.remove(); } catch (e) {} }, 260);
        }
        // quick subtotal update
        let newSubtotal = 0;
        document.querySelectorAll('#cart-items [data-cart-line-total]').forEach(el => newSubtotal += parseCurrency(el.textContent));
        const subtotalEl = document.querySelector('[data-cart-subtotal]'); if (subtotalEl) subtotalEl.textContent = formatCurrency(newSubtotal);
        const totalEl = document.querySelector('[data-cart-total]'); if (totalEl) totalEl.textContent = formatCurrency(newSubtotal);

        const data = await cartRemove(opts);
        if (data && data.success) reconcileServerData(data, 'Đã xoá khỏi giỏ hàng');
        return data;
    } catch (err) {
        try { applySnapshot(snapshot); } catch (e) {}
        console.error('cartPerformRemove error', err);
        if (window.showToast) window.showToast('Lỗi khi xoá sản phẩm', { type: 'error' });
        throw err;
    }
}

// keep globals for backwards compatibility
window.cartPerformUpdate = cartPerformUpdate;
window.cartPerformRemove = cartPerformRemove;

// Sync checkout page's order-summary / aside using cart payload
function syncCheckoutFromPayload(cartArray) {
    try {
        if (!cartArray) return;
        const map = {};
        if (Array.isArray(cartArray)) {
            cartArray.forEach(it => { if (it && it.id != null) map[String(it.id)] = it; });
        } else {
            Object.keys(cartArray).forEach(k => { map[String(k)] = cartArray[k]; });
        }

        const aside = document.querySelector('aside .sticky') || document.querySelector('aside');
        if (!aside) return;
        const list = aside.querySelector('ul');
        if (!list) return;

        // Update existing rows and remove missing ones
        let subtotal = 0;
        const existingRows = Array.from(list.querySelectorAll('[data-product-id]'));
        const seen = new Set();
        existingRows.forEach(row => {
            const id = row.getAttribute('data-product-id');
            if (!id) return;
            const item = map[String(id)];
            if (!item) {
                // removed on server
                try { row.remove(); } catch (e) {}
                return;
            }
            seen.add(String(id));
            const qty = parseInt(item.qty || item.quantity || 0, 10) || 0;
            const price = parseInt(item.price || 0, 10) || 0;
            const line = parseInt(item.total || (price * qty), 10) || (price * qty);
            subtotal += line;

            // update attributes and displays
            row.setAttribute('data-price', String(price));
            const qtyEl = row.querySelector('[data-cart-qty]'); if (qtyEl) qtyEl.textContent = String(qty);
            const qtyDisplay = row.querySelector('[data-cart-qty-display]'); if (qtyDisplay) qtyDisplay.textContent = String(qty);
            const lineEl = row.querySelector('[data-cart-line-total]'); if (lineEl) lineEl.textContent = formatCurrency(line);
        });

        // Create missing rows (use same structure as server-rendered Blade)
        Object.keys(map).forEach(id => {
            if (seen.has(id)) return;
            const it = map[id];
            const qty = parseInt(it.qty || it.quantity || 0, 10) || 0;
            const price = parseInt(it.price || 0, 10) || 0;
            const line = parseInt(it.total || (price * qty), 10) || (price * qty);
            subtotal += line;

            const li = document.createElement('li');
            li.setAttribute('data-product-id', String(it.id));
            li.setAttribute('data-price', String(price));
            li.className = 'flex items-center justify-between py-3';
            li.innerHTML = `
                <div class="flex min-w-0 items-center gap-3">
                    ${(it.image) ? `<img src="${it.image}" class="h-12 w-12 rounded object-cover" alt="${(it.name||'')}">` : ''}
                    <div class="min-w-0">
                        <div class="truncate text-sm font-medium text-gray-900">${(it.name||'Product')}</div>
                        <div class="text-xs text-gray-500">x<span data-cart-qty>${qty}</span></div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center rounded-md border overflow-hidden">
                        <button type="button" onclick="window.cartPerformUpdate({ url: '/cart/update', product_id: ${it.id}, quantity: ${Math.max(qty-1,0)} })" class="px-3 py-1 bg-gray-100 text-sm" aria-label="Decrease quantity">−</button>
                        <div class="px-4 py-1 text-sm" aria-live="polite" data-cart-qty-display>${qty}</div>
                        <button type="button" onclick="window.cartPerformUpdate({ url: '/cart/update', product_id: ${it.id}, quantity: ${qty+1} })" class="px-3 py-1 bg-gray-100 text-sm" aria-label="Increase quantity">+</button>
                    </div>
                    <div class="text-sm font-semibold" data-cart-line-total>${formatCurrency(line)}</div>
                    <button type="button" onclick="window.cartPerformRemove({ url: '/cart/remove', product_id: ${it.id} })" class="inline-flex items-center justify-center h-8 w-8 rounded bg-red-500 text-white" aria-label="Remove product">✕</button>
                </div>
            `;
            list.appendChild(li);
        });

        // update subtotal display (prefer data-checkout-subtotal)
        const subtotalEl = aside.querySelector('[data-checkout-subtotal]');
        if (subtotalEl) {
            subtotalEl.textContent = formatCurrency(subtotal);
        } else {
            // fallback: try to find nearby label and update sibling
            const nodes = aside.querySelectorAll('div, span');
            nodes.forEach(node => {
                try {
                    const txt = (node.textContent || '').trim();
                    if (!txt) return;
                    if (/Tạm tính|Subtotal|Tạm tính:/i.test(txt)) {
                        const sibling = node.nextElementSibling;
                        if (sibling) sibling.textContent = formatCurrency(subtotal);
                    }
                } catch (e) {}
            });
        }
    } catch (e) {
        // tolerate failures silently
        console.error('syncCheckoutFromPayload error', e);
    }
}
