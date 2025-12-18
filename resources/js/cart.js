// Lightweight cart helper used by Blade views.
// Exposes a global `cartAdd` function that accepts an object:{ url, product_id, quantity }
export async function cartAdd({ url, product_id, quantity }) {
    if (!url) throw new Error('cartAdd: url is required');
    try {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        const token = tokenEl ? tokenEl.getAttribute('content') : null;

        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(token ? { 'X-CSRF-TOKEN': token } : {})
            },
            body: JSON.stringify({ product_id: product_id, quantity: quantity })
        });

        if (!res.ok) {
            const text = await res.text();
            throw new Error('Request failed: ' + res.status + '\n' + text);
        }

        const data = await res.json();
        return data;
    } catch (err) {
        console.error('cartAdd error', err);
        throw err;
    }
}

// Expose as global for inline handlers in Blade files
window.cartAdd = cartAdd;

export async function cartUpdate({ url, product_id, quantity }) {
    if (!url) throw new Error('cartUpdate: url is required');
    try {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        const token = tokenEl ? tokenEl.getAttribute('content') : null;
        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(token ? { 'X-CSRF-TOKEN': token } : {})
            },
            body: JSON.stringify({ product_id: product_id, quantity: quantity })
        });
        if (!res.ok) {
            const text = await res.text();
            throw new Error('Request failed: ' + res.status + '\n' + text);
        }
        const data = await res.json();
        return data;
    } catch (err) {
        console.error('cartUpdate error', err);
        throw err;
    }
}

export async function cartRemove({ url, product_id }) {
    if (!url) throw new Error('cartRemove: url is required');
    try {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        const token = tokenEl ? tokenEl.getAttribute('content') : null;
        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(token ? { 'X-CSRF-TOKEN': token } : {})
            },
            body: JSON.stringify({ product_id: product_id })
        });
        if (!res.ok) {
            const text = await res.text();
            throw new Error('Request failed: ' + res.status + '\n' + text);
        }
        const data = await res.json();
        return data;
    } catch (err) {
        console.error('cartRemove error', err);
        throw err;
    }
}

window.cartUpdate = cartUpdate;
window.cartRemove = cartRemove;

export async function cartPerformUpdate(opts) {
    try {
        const data = await cartUpdate(opts);
        if (data && data.success) {
            const dropdown = document.querySelector('[x-html]');
            if (dropdown && data.html) dropdown.innerHTML = data.html;
            // Update or remove badges depending on count
            document.querySelectorAll('[data-cart-badge]').forEach(function (b) {
                if ((data.count || 0) > 0) {
                    b.textContent = data.count;
                    b.style.display = '';
                } else {
                    // remove stale badge
                    b.remove();
                }
            });
            if (window.showToast) window.showToast('Cập nhật giỏ hàng');
        }
        return data;
    } catch (err) {
        console.error('cartPerformUpdate error', err);
        if (window.showToast) window.showToast('Lỗi khi cập nhật giỏ hàng', { type: 'error' });
        throw err;
    }
}

export async function cartPerformRemove(opts) {
    try {
        const data = await cartRemove(opts);
        if (data && data.success) {
            const dropdown = document.querySelector('[x-html]');
            if (dropdown && data.html) dropdown.innerHTML = data.html;
            // Update or remove badges depending on count
            document.querySelectorAll('[data-cart-badge]').forEach(function (b) {
                if ((data.count || 0) > 0) {
                    b.textContent = data.count;
                    b.style.display = '';
                } else {
                    b.remove();
                }
            });
            if (window.showToast) window.showToast('Đã xoá khỏi giỏ hàng');
        }
        return data;
    } catch (err) {
        console.error('cartPerformRemove error', err);
        if (window.showToast) window.showToast('Lỗi khi xoá sản phẩm', { type: 'error' });
        throw err;
    }
}

window.cartPerformUpdate = cartPerformUpdate;
window.cartPerformRemove = cartPerformRemove;
