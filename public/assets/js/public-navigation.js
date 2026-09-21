export function initializeNavigation() {
    const header = document.querySelector('.public-header');
    const menuButton = document.querySelector('[data-public-menu]');
    const mobileMenu = document.querySelector('[data-public-mobile]');
    if (!header || !menuButton || !mobileMenu || menuButton.dataset.navigationReady) return;
    menuButton.dataset.navigationReady = 'true';

    const desktop = window.matchMedia('(min-width: 1051px)');
    const setOpen = (open) => {
        mobileMenu.classList.toggle('open', open);
        menuButton.setAttribute('aria-expanded', String(open));
        menuButton.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    };
    const updateHeight = () => {
        const height = header.getBoundingClientRect().height;
        document.body.style.setProperty('--public-header-height', `${height}px`);
        document.documentElement.style.scrollPaddingTop = `${height + 16}px`;
    };

    updateHeight();
    if ('ResizeObserver' in window) new ResizeObserver(updateHeight).observe(header);
    window.addEventListener('resize', updateHeight);
    menuButton.addEventListener('click', () => setOpen(!mobileMenu.classList.contains('open')));
    mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('click', event => {
        if (!header.contains(event.target) && !mobileMenu.contains(event.target)) setOpen(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && mobileMenu.classList.contains('open')) {
            setOpen(false);
            menuButton.focus();
        }
    });
    desktop.addEventListener('change', () => { if (desktop.matches) setOpen(false); });
}
