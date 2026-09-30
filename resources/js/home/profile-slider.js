import Swiper from 'swiper';
import { A11y, Autoplay, EffectCreative, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-creative';
import 'swiper/css/a11y';

export function initializeProfileSlider() {
    const section = document.querySelector('[data-profile-slider]');
    const element = section?.querySelector('.featuredSwiper');
    if (!element || element.swiper) return;

    const count = element.querySelectorAll('.swiper-slide').length;
    if (!count) return;
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    // Small collections can rewind without duplicating member profiles.
    const loop = count >= 7;
    const swiper = new Swiper(element, {
        modules: [A11y, Autoplay, EffectCreative, Navigation, Pagination],
        effect: 'creative',
        slidesPerView: 'auto',
        centeredSlides: true,
        initialSlide: loop ? 0 : Math.floor(count / 2),
        loop,
        rewind: !loop,
        speed: motion.matches ? 0 : 600,
        grabCursor: count > 1,
        watchOverflow: true,
        creativeEffect: {
            perspective: false,
            limitProgress: 3,
            prev: { translate: ['-85%', 24, 0], scale: 0.76, opacity: 0.8 },
            next: { translate: ['85%', 24, 0], scale: 0.76, opacity: 0.8 },
        },
        autoplay: {
            enabled: !motion.matches && count > 1,
            delay: 4500,
            disableOnInteraction: true,
            pauseOnMouseEnter: true,
        },
        navigation: {
            prevEl: section.querySelector('.featured-swiper-prev'),
            nextEl: section.querySelector('.featured-swiper-next'),
        },
        pagination: {
            el: section.querySelector('.featured-swiper-pagination'),
            clickable: true,
            bulletElement: 'button',
            renderBullet: (index, className) => `<button type="button" class="${className}" aria-label="Show profile ${index + 1}"></button>`,
        },
        a11y: {
            prevSlideMessage: 'Previous profile',
            nextSlideMessage: 'Next profile',
            paginationBulletMessage: 'Show profile {{index}}',
        },
    });

    element.addEventListener('keydown', (event) => {
        if (event.target !== element) return;
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            swiper.autoplay.stop();
            event.key === 'ArrowRight' ? swiper.slideNext() : swiper.slidePrev();
        }
    });
    section.addEventListener('focusin', () => swiper.autoplay.stop());
    motion.addEventListener('change', () => {
        swiper.params.speed = motion.matches ? 0 : 600;
        if (motion.matches) swiper.autoplay.stop();
    });
}
