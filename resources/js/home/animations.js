export function initializeRevealAnimations() {
    const revealElements = Array.from(document.querySelectorAll(
        'section, .profile-card, .story-grid article, .community-grid a, .app-cta, .trust-strip article, .finder'
    )).filter((element) => !element.closest('[data-profile-slider]'));

    revealElements.forEach((element) => {
        element.classList.add('reveal');
    });

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

        revealElements.forEach((element) => revealObserver.observe(element));

        return;
    }

    revealElements.forEach((element) => element.classList.add('is-visible'));
}
