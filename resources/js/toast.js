// Minimal toast utility. No external dependency required.
function ensureContainer() {
    let c = document.getElementById('site-toast-container');
    if (!c) {
        c = document.createElement('div');
        c.id = 'site-toast-container';
        Object.assign(c.style, {
            position: 'fixed',
            right: '1rem',
            top: '1rem',
            zIndex: 99999,
            display: 'flex',
            flexDirection: 'column',
            gap: '0.5rem',
            pointerEvents: 'none'
        });
        document.body.appendChild(c);
    }
    return c;
}

export function showToast(message, { duration = 3000, type = 'default' } = {}) {
    const container = ensureContainer();
    const toast = document.createElement('div');
    toast.className = 'site-toast';
    toast.textContent = message;
    Object.assign(toast.style, {
        pointerEvents: 'auto',
        minWidth: '180px',
        maxWidth: '320px',
        background: type === 'error' ? '#ef4444' : '#111827',
        color: '#fff',
        padding: '0.6rem 1rem',
        borderRadius: '0.5rem',
        boxShadow: '0 6px 18px rgba(0,0,0,0.12)',
        opacity: '0',
        transform: 'translateY(-6px)',
        transition: 'opacity 220ms ease, transform 220ms ease',
        fontSize: '0.95rem',
        lineHeight: '1.2'
    });
    container.appendChild(toast);
    // show
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    });
    const hide = () => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-6px)';
        setTimeout(() => { try { container.removeChild(toast); } catch (e) {} }, 240);
    };
    const t = setTimeout(hide, duration);
    // allow click to dismiss
    toast.addEventListener('click', function () { clearTimeout(t); hide(); });
    return { hide };
}

window.showToast = showToast;
