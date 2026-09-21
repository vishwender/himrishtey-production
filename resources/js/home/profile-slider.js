export function initializeProfileSlider() {
    const slider = document.querySelector('[data-profile-slider]');
    if (!slider) return;
    const track = slider.querySelector('.profile-slider-track');
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let paused = motion.matches;
    let timer;
    const cards = Array.from(track.children);
    const dots = document.createElement('div');
    dots.className = 'profile-carousel-dots';
    dots.setAttribute('aria-label', 'Choose carousel position');
    track.after(dots);
    const viewport = document.createElement('div');
    viewport.className = 'profile-carousel-viewport';
    track.before(viewport);
    viewport.append(track);
    const frame = document.createElement('div');
    frame.className = 'profile-carousel-frame';
    viewport.before(frame);
    frame.append(viewport);
    frame.append(slider.querySelector('.profile-slider-controls'));
    slider.classList.add('carousel-ready');
    let position = 0;
    let size = 0;
    let positions = 1;
    const show = (next) => {
        position = (next + positions) % positions;
        const step = cards[0].getBoundingClientRect().width + 20;
        track.style.transform = `translateX(-${position * step}px)`;
        cards.forEach((card, index) => {
            const visible = index >= position && index < position + size;
            card.inert = !visible;
            card.setAttribute('aria-hidden', String(!visible));
        });
        Array.from(dots.children).forEach((dot, index) => {
            dot.setAttribute('aria-current', String(index === position));
        });
    };
    const rebuild = () => {
        size = Math.min(cards.length, window.innerWidth <= 700 ? 2 : window.innerWidth <= 1100 ? 4 : 5);
        positions = Math.max(1, cards.length - size + 1);
        track.style.setProperty('--cards-per-page', size);
        dots.replaceChildren();
        for (let index = 0; index < positions; index++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.setAttribute('aria-label', `Show profiles starting at ${index + 1}`);
            dot.addEventListener('click', () => show(index));
            dots.append(dot);
        }
        show(Math.min(position, positions - 1));
    };
    const move = (direction) => show(position + direction);
    rebuild();
    window.addEventListener('resize', rebuild);
    let touchX;
    viewport.addEventListener('touchstart', (event) => { touchX = event.changedTouches[0].clientX; }, { passive: true });
    viewport.addEventListener('touchend', (event) => {
        const delta = event.changedTouches[0].clientX - touchX;
        if (Math.abs(delta) > 45) move(delta < 0 ? 1 : -1);
    }, { passive: true });
    const stop = () => clearInterval(timer);
    const start = () => {
        stop();
        if (!paused && !document.hidden && !track.contains(document.activeElement)) {
            timer = setInterval(() => move(1), 4000);
        }
    };
    slider.querySelector('.profile-slider-controls').hidden = false;
    slider.querySelector('[data-slider-prev]').addEventListener('click', () => move(-1));
    slider.querySelector('[data-slider-next]').addEventListener('click', () => move(1));
    track.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            move(event.key === 'ArrowRight' ? 1 : -1);
        }
    });
    track.addEventListener('focusin', stop);
    slider.addEventListener('focusout', () => setTimeout(start, 0));
    slider.addEventListener('touchstart', stop, { passive: true });
    slider.addEventListener('touchend', start, { passive: true });
    slider.addEventListener('touchcancel', start, { passive: true });
    document.addEventListener('visibilitychange', start);
    motion.addEventListener('change', () => { paused = motion.matches; start(); });
    start();
}
