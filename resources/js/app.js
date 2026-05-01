import './bootstrap';

const initializePasswordToggles = () => {
    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        if (!(toggle instanceof HTMLButtonElement) || toggle.dataset.initialized === 'true') {
            return;
        }

        const targetId = toggle.dataset.passwordTarget;

        if (!targetId) {
            return;
        }

        const input = document.getElementById(targetId);

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const showIcon = toggle.querySelector('[data-password-icon="show"]');
        const hideIcon = toggle.querySelector('[data-password-icon="hide"]');

        const setVisibility = (isVisible) => {
            input.type = isVisible ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
            toggle.setAttribute(
                'aria-label',
                isVisible ? 'Hide password' : 'Show password',
            );

            showIcon?.classList.toggle('hidden', isVisible);
            hideIcon?.classList.toggle('hidden', !isVisible);
        };

        setVisibility(false);

        toggle.addEventListener('click', () => {
            setVisibility(input.type === 'password');
        });

        toggle.dataset.initialized = 'true';
    });
};

const initializeProductGalleries = () => {
    document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
        if (!(gallery instanceof HTMLElement) || gallery.dataset.initialized === 'true') {
            return;
        }

        const mainImage = gallery.querySelector('[data-product-main-image]');
        const thumbnails = gallery.querySelectorAll('[data-product-thumb]');

        if (!(mainImage instanceof HTMLImageElement)) {
            return;
        }

        thumbnails.forEach((thumbnail) => {
            if (!(thumbnail instanceof HTMLButtonElement)) {
                return;
            }

            thumbnail.addEventListener('click', () => {
                const nextSource = thumbnail.dataset.productThumbSrc;
                const nextAlt = thumbnail.dataset.productThumbAlt;

                if (!nextSource) {
                    return;
                }

                mainImage.src = nextSource;
                mainImage.alt = nextAlt || mainImage.alt;

                thumbnails.forEach((item) => {
                    item.classList.remove('border-love-pink-400');
                    item.classList.add('border-transparent');
                    item.setAttribute('aria-pressed', 'false');
                });

                thumbnail.classList.add('border-love-pink-400');
                thumbnail.classList.remove('border-transparent');
                thumbnail.setAttribute('aria-pressed', 'true');
            });
        });

        gallery.dataset.initialized = 'true';
    });
};

const initializeProductQuantities = () => {
    document.querySelectorAll('[data-product-quantity]').forEach((quantityControl) => {
        if (!(quantityControl instanceof HTMLElement) || quantityControl.dataset.initialized === 'true') {
            return;
        }

        const input = quantityControl.querySelector('[data-quantity-input]');
        const decrease = quantityControl.querySelector('[data-quantity-decrease]');
        const increase = quantityControl.querySelector('[data-quantity-increase]');

        if (!(input instanceof HTMLInputElement) || !(decrease instanceof HTMLButtonElement) || !(increase instanceof HTMLButtonElement)) {
            return;
        }

        const min = Number.parseInt(input.min || '1', 10);
        const max = Number.parseInt(input.max || '99', 10);

        const setValue = (nextValue) => {
            const safeValue = Math.min(max, Math.max(min, nextValue));

            input.value = safeValue.toString();
            decrease.disabled = safeValue <= min;
            increase.disabled = safeValue >= max;
        };

        decrease.addEventListener('click', () => {
            setValue(Number.parseInt(input.value || min.toString(), 10) - 1);
        });

        increase.addEventListener('click', () => {
            setValue(Number.parseInt(input.value || min.toString(), 10) + 1);
        });

        input.addEventListener('input', () => {
            setValue(Number.parseInt(input.value || min.toString(), 10));
        });

        setValue(Number.parseInt(input.value || min.toString(), 10));
        quantityControl.dataset.initialized = 'true';
    });
};

const initializeReviewPagination = () => {
    document.querySelectorAll('[data-review-section]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const reviews = section.querySelectorAll('[data-review-page]');
        const buttons = section.querySelectorAll('[data-review-page-button]');
        const status = section.querySelector('[data-review-pagination-status]');
        const perPage = 5;

        const showPage = (page) => {
            reviews.forEach((review) => {
                if (!(review instanceof HTMLElement)) {
                    return;
                }

                review.classList.toggle('hidden', review.dataset.reviewPage !== page);
            });

            buttons.forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.reviewPageButton === page;

                button.classList.toggle('border-love-pink-300', isActive);
                button.classList.toggle('bg-love-pink-100', isActive);
                button.classList.toggle('text-love-pink-500', isActive);
                button.classList.toggle('border-slate-200', !isActive);
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('text-slate-600', !isActive);
                button.setAttribute('aria-current', isActive ? 'page' : 'false');
            });

            if (status instanceof HTMLElement) {
                const currentPage = Number.parseInt(page, 10);
                const totalReviews = reviews.length;
                const start = (currentPage - 1) * perPage + 1;
                const end = Math.min(currentPage * perPage, totalReviews);

                status.textContent = `Showing ${start}-${end} of ${totalReviews} reviews`;
            }
        };

        buttons.forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.addEventListener('click', () => {
                showPage(button.dataset.reviewPageButton || '1');
            });
        });

        showPage('1');
        section.dataset.initialized = 'true';
    });
};

const initializeReviewRatings = () => {
    document.querySelectorAll('[data-review-rating]').forEach((ratingControl) => {
        if (!(ratingControl instanceof HTMLElement) || ratingControl.dataset.initialized === 'true') {
            return;
        }

        const input = ratingControl.querySelector('[data-review-rating-input]');
        const stars = ratingControl.querySelectorAll('[data-review-star]');

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const setRating = (rating) => {
            input.value = rating.toString();

            stars.forEach((star) => {
                if (!(star instanceof HTMLButtonElement)) {
                    return;
                }

                const starValue = Number.parseInt(star.dataset.reviewStar || '0', 10);
                const isActive = starValue <= rating;

                star.classList.toggle('text-amber-400', isActive);
                star.classList.toggle('text-slate-200', !isActive);
                star.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        stars.forEach((star) => {
            if (!(star instanceof HTMLButtonElement)) {
                return;
            }

            star.addEventListener('click', () => {
                setRating(Number.parseInt(star.dataset.reviewStar || '5', 10));
            });
        });

        setRating(Number.parseInt(input.value || '5', 10));
        ratingControl.dataset.initialized = 'true';
    });
};

const initializeReviewForms = () => {
    document.querySelectorAll('[data-review-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.initialized === 'true') {
            return;
        }

        const status = form.querySelector('[data-review-form-status]');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (status instanceof HTMLElement) {
                status.textContent = 'Thanks for sharing your review.';
                status.classList.remove('opacity-0');
            }
        });

        form.dataset.initialized = 'true';
    });
};

const initializeContactForms = () => {
    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.initialized === 'true') {
            return;
        }

        const status = form.querySelector('[data-contact-form-status]');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            const formData = new FormData(form);
            const name = formData.get('name')?.toString() || '';
            const email = formData.get('email')?.toString() || '';
            const concern = formData.get('concern')?.toString() || 'Customer message';
            const orderNumber = formData.get('order_number')?.toString() || 'Not provided';
            const message = formData.get('message')?.toString() || '';
            const contactEmail = form.dataset.contactEmail || 'hello@lovebyade.test';
            const subject = encodeURIComponent(`[Loveby_Ade Contact] ${concern}`);
            const body = encodeURIComponent([
                `Name: ${name}`,
                `Email: ${email}`,
                `Concern: ${concern}`,
                `Order number: ${orderNumber}`,
                '',
                message,
            ].join('\n'));

            if (status instanceof HTMLElement) {
                status.textContent = 'Opening your email app for admin contact.';
                status.classList.remove('opacity-0');
            }

            window.location.href = `mailto:${contactEmail}?subject=${subject}&body=${body}`;
        });

        form.dataset.initialized = 'true';
    });
};

const initializeStorefrontInteractions = () => {
    initializePasswordToggles();
    initializeProductGalleries();
    initializeProductQuantities();
    initializeReviewPagination();
    initializeReviewRatings();
    initializeReviewForms();
    initializeContactForms();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeStorefrontInteractions);
} else {
    initializeStorefrontInteractions();
}
