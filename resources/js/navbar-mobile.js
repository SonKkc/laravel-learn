// Simple JS for mobile navbar dropdown
// Toggle mobile menu visibility

document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById('navbar-mobile-toggle');
    const mobileMenuWrapper = document.getElementById('navbar-mobile-menu');
    const navbarContainer = document.querySelector('.navbar__container');

    if (!menuBtn || !mobileMenuWrapper || !navbarContainer) return;

    // Hiệu ứng xổ xuống cho menu
    mobileMenuWrapper.style.maxHeight = '0';
    mobileMenuWrapper.style.overflow = 'hidden';
    mobileMenuWrapper.style.opacity = '0';
    mobileMenuWrapper.style.transition = 'max-height 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.3s';
    mobileMenuWrapper.style.pointerEvents = 'none';

    menuBtn.addEventListener('click', function () {
        const isOpen = mobileMenuWrapper.style.maxHeight !== '0' && mobileMenuWrapper.style.maxHeight !== '0px';
        if (!isOpen) {
            mobileMenuWrapper.style.maxHeight = mobileMenuWrapper.scrollHeight + 'px';
            mobileMenuWrapper.style.opacity = '1';
            mobileMenuWrapper.style.pointerEvents = 'auto';
            navbarContainer.classList.add('navbar-mobile-open');
        } else {
            mobileMenuWrapper.style.maxHeight = '0';
            mobileMenuWrapper.style.opacity = '0';
            mobileMenuWrapper.style.pointerEvents = 'none';
            navbarContainer.classList.remove('navbar-mobile-open');
        }
    });

    // Đóng menu khi nhấn ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            mobileMenuWrapper.style.maxHeight = '0';
            mobileMenuWrapper.style.opacity = '0';
            mobileMenuWrapper.style.pointerEvents = 'none';
            navbarContainer.classList.remove('navbar-mobile-open');
        }
    });
});
