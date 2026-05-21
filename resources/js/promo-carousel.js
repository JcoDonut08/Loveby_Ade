const AUTO_ADVANCE_DELAY = 5000;

const initializePromoCarousels = () => {
    document.querySelectorAll('[data-promo-carousel]').forEach((carousel) => {
        if (!(carousel instanceof HTMLElement) || carousel.dataset.initialized === 'true') {
            return;
        }

        const track = carousel.querySelector('[data-promo-track]');
        const slides = Array.from(carousel.querySelectorAll('[data-promo-slide]')).filter((slide) => slide instanceof HTMLElement);
        const dots = Array.from(carousel.querySelectorAll('[data-promo-dot]')).filter((dot) => dot instanceof HTMLButtonElement);
        const previous = carousel.querySelector('[data-promo-previous]');
        const next = carousel.querySelector('[data-promo-next]');
        let activeIndex = 0;
        let autoAdvance;
        let scrollTimeout;

        if (!(track instanceof HTMLElement) || slides.length < 2) {
            carousel.dataset.initialized = 'true';

            return;
        }

        const setActiveDot = () => {
            dots.forEach((dot, index) => {
                dot.dataset.active = index === activeIndex ? 'true' : 'false';
            });
        };

        const showSlide = (index, behavior = 'smooth') => {
            activeIndex = (index + slides.length) % slides.length;
            track.scrollTo({
                left: slides[activeIndex].offsetLeft,
                behavior,
            });
            setActiveDot();
        };

        const restartAutoAdvance = () => {
            window.clearInterval(autoAdvance);
            autoAdvance = window.setInterval(() => {
                showSlide(activeIndex + 1);
            }, AUTO_ADVANCE_DELAY);
        };

        const handleManualNavigation = (index) => {
            showSlide(index);
            restartAutoAdvance();
        };

        previous?.addEventListener('click', () => {
            handleManualNavigation(activeIndex - 1);
        });

        next?.addEventListener('click', () => {
            handleManualNavigation(activeIndex + 1);
        });

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const index = Number.parseInt(dot.dataset.promoIndex || '0', 10);
                handleManualNavigation(index);
            });
        });

        track.addEventListener('scroll', () => {
            window.clearTimeout(scrollTimeout);
            scrollTimeout = window.setTimeout(() => {
                const nearestIndex = slides.reduce((closestIndex, slide, index) => {
                    const closestDistance = Math.abs(slides[closestIndex].offsetLeft - track.scrollLeft);
                    const distance = Math.abs(slide.offsetLeft - track.scrollLeft);

                    return distance < closestDistance ? index : closestIndex;
                }, activeIndex);

                activeIndex = nearestIndex;
                setActiveDot();
            }, 120);
        });

        setActiveDot();
        restartAutoAdvance();
        carousel.dataset.initialized = 'true';
    });
};

export { initializePromoCarousels };
