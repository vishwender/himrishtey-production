export function initializeNavigation() {
    const menuButton = document.querySelector('[data-public-menu]');
    const mobileMenu = document.querySelector('[data-public-mobile]');

    if (!menuButton || !mobileMenu) {
        return;
    }

    menuButton.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.toggle('open');

        menuButton.setAttribute('aria-expanded', String(isOpen));
        menuButton.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Open navigation');
        });
    });
}
