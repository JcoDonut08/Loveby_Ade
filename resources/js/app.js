import './bootstrap';
import { initializeAdminAnalytics } from './admin-analytics';
import { initializeAdminCustomers } from './admin-customers';
import { initializeAdminDashboard } from './admin-dashboard';
import { initializeAdminNotifications } from './admin-notifications';
import { initializeAdminOrderManagement } from './admin-orders';
import { initializeAdminProducts } from './admin-products';

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

const initializeAutoFilterForms = () => {
    document.querySelectorAll('[data-auto-filter-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.initialized === 'true') {
            return;
        }

        let timeoutId;

        const submit = (delay = 0) => {
            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(() => {
                form.requestSubmit();
            }, delay);
        };

        form.querySelectorAll('select').forEach((select) => {
            select.addEventListener('change', () => submit());
        });

        form.querySelectorAll('input').forEach((input) => {
            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            input.addEventListener(input.type === 'search' ? 'input' : 'change', () => {
                submit(input.type === 'search' ? 450 : 0);
            });
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

const initializeOtpInputs = () => {
    document.querySelectorAll('[data-otp-inputs]').forEach((group) => {
        if (!(group instanceof HTMLElement) || group.dataset.initialized === 'true') {
            return;
        }

        const inputs = Array.from(group.querySelectorAll('input')).filter((input) => input instanceof HTMLInputElement);

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(0, 1);

                if (input.value && inputs[index + 1]) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && inputs[index - 1]) {
                    inputs[index - 1].focus();
                }
            });
        });

        group.dataset.initialized = 'true';
    });
};

const initializeFavoriteToggles = () => {
    const setFavoriteNavCount = (count) => {
        document.querySelectorAll('[data-favorites-nav-count]').forEach((counter) => {
            if (!(counter instanceof HTMLElement)) {
                return;
            }

            counter.textContent = count.toString();
            counter.classList.toggle('hidden', count < 1);

            if (count > 0) {
                counter.classList.add('scale-125', 'ring-4', 'ring-love-pink-100');

                window.setTimeout(() => {
                    counter.classList.remove('scale-125', 'ring-4', 'ring-love-pink-100');
                }, 420);
            }
        });
    };

    const updateFavoritesEmptyState = () => {
        const grid = document.querySelector('[data-favorites-grid]');
        const emptyState = document.querySelector('[data-favorites-empty]');
        const countLabel = document.querySelector('[data-favorites-count]');

        if (!(grid instanceof HTMLElement)) {
            return;
        }

        const count = grid.querySelectorAll('[data-favorite-card]').length;

        if (countLabel instanceof HTMLElement) {
            countLabel.textContent = `${count} ${count === 1 ? 'saved item' : 'saved items'}`;
        }

        if (emptyState instanceof HTMLElement) {
            emptyState.classList.toggle('hidden', count > 0);
        }

        grid.classList.toggle('hidden', count < 1);
    };

    const setActive = (button, isActive) => {
        const icon = button.querySelector('[data-favorite-icon]');

        button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        button.classList.toggle('border-love-pink-500', isActive);
        button.classList.toggle('bg-love-pink-500', isActive);
        button.classList.toggle('text-white', isActive);
        button.classList.toggle('border-transparent', !isActive);
        button.classList.toggle('bg-white/92', !isActive);
        button.classList.toggle('text-slate-500', !isActive);
        button.classList.toggle('hover:text-love-pink-500', !isActive);
        icon?.classList.toggle('fill-current', isActive);
        icon?.classList.toggle('fill-transparent', !isActive);
    };

    const animateButton = (button, isActive) => {
        button.classList.add('scale-110', 'ring-4', isActive ? 'ring-love-pink-100' : 'ring-slate-100');

        window.setTimeout(() => {
            button.classList.remove('scale-110', 'ring-4', 'ring-love-pink-100', 'ring-slate-100');
        }, 420);
    };

    const syncFavoriteButtons = (slugs) => {
        document.querySelectorAll('[data-favorite-toggle]').forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const slug = button.dataset.productSlug;

            if (!slug) {
                return;
            }

            setActive(button, slugs.includes(slug));
        });
    };

    const removeFavoriteCard = (card) => {
        card.classList.add('scale-[0.98]', 'opacity-0');

        window.setTimeout(() => {
            card.remove();
            updateFavoritesEmptyState();
        }, 220);
    };

    const applyFavoriteSummary = (summary) => {
        setFavoriteNavCount(summary.count || 0);
        syncFavoriteButtons(summary.slugs || []);
    };

    const fetchFavoriteSummary = async () => {
        const response = await window.axios.get('/favorites/summary');

        applyFavoriteSummary(response.data);

        return response.data;
    };

    const toggleFavoriteItem = async (slug) => {
        const response = await window.axios.post('/favorites/items', {
            slug,
        });

        applyFavoriteSummary(response.data);

        return response.data;
    };

    const removeFavoriteItem = async (slug) => {
        const response = await window.axios.delete(`/favorites/items/${slug}`);

        applyFavoriteSummary(response.data);

        return response.data;
    };

    document.querySelectorAll('[data-favorite-toggle]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement) || button.dataset.initialized === 'true') {
            return;
        }

        button.addEventListener('click', async () => {
            const slug = button.dataset.productSlug;

            if (!slug) {
                setActive(button, button.getAttribute('aria-pressed') !== 'true');
                animateButton(button, button.getAttribute('aria-pressed') === 'true');

                return;
            }

            button.disabled = true;

            try {
                const response = await toggleFavoriteItem(slug);
                const isActive = response.favorited === true;

                setActive(button, isActive);
                animateButton(button, isActive);

                const card = button.closest('[data-favorite-card]');

                if (card instanceof HTMLElement && !isActive) {
                    removeFavoriteCard(card);
                }
            } finally {
                button.disabled = false;
            }
        });

        setActive(button, button.getAttribute('aria-pressed') === 'true');
        button.dataset.initialized = 'true';
    });

    document.querySelectorAll('[data-favorite-remove]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement) || button.dataset.initialized === 'true') {
            return;
        }

        button.addEventListener('click', async () => {
            const slug = button.dataset.productSlug;
            const card = button.closest('[data-favorite-card]');

            if (!slug) {
                card?.remove();
                updateFavoritesEmptyState();

                return;
            }

            button.disabled = true;

            try {
                await removeFavoriteItem(slug);

                if (card instanceof HTMLElement) {
                    removeFavoriteCard(card);
                }
            } finally {
                button.disabled = false;
            }
        });

        button.dataset.initialized = 'true';
    });

    if (document.querySelector('[data-favorite-toggle], [data-favorites-nav-count], [data-favorites-grid]')) {
        fetchFavoriteSummary().catch(() => {});
    }

    updateFavoritesEmptyState();
};

const setCartNavCount = (count) => {
    document.querySelectorAll('[data-cart-nav-count]').forEach((counter) => {
        if (!(counter instanceof HTMLElement)) {
            return;
        }

        counter.textContent = count.toString();
        counter.classList.toggle('hidden', count < 1);

        if (count > 0) {
            counter.classList.add('scale-125', 'ring-4', 'ring-love-pink-100');

            window.setTimeout(() => {
                counter.classList.remove('scale-125', 'ring-4', 'ring-love-pink-100');
            }, 420);
        }
    });
};

const addCartItem = async ({ slug, quantity = 1 }) => {
    const response = await window.axios.post('/cart/items', {
        slug,
        quantity,
    });

    setCartNavCount(response.data.count || 0);

    return response.data;
};

const updateCartItem = async ({ slug, quantity }) => {
    const response = await window.axios.patch(`/cart/items/${slug}`, {
        quantity,
    });

    setCartNavCount(response.data.count || 0);

    return response.data;
};

const removeCartItem = async (slug) => {
    const response = await window.axios.delete(`/cart/items/${slug}`);

    setCartNavCount(response.data.count || 0);

    return response.data;
};

const initializeAddToCartButtons = () => {
    document.querySelectorAll('[data-add-to-cart]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement) || button.dataset.initialized === 'true') {
            return;
        }

        const label = button.querySelector('[data-add-to-cart-label]') || button;
        const defaultLabel = label.textContent || 'Add to cart';

        button.addEventListener('click', async () => {
            const slug = button.dataset.productSlug;

            if (!slug) {
                return;
            }

            const quantitySource = button.dataset.quantitySource
                ? document.querySelector(button.dataset.quantitySource)
                : null;
            const quantity = quantitySource instanceof HTMLInputElement
                ? Number.parseInt(quantitySource.value || '1', 10)
                : 1;

            button.disabled = true;
            button.classList.add('scale-[1.02]', 'bg-love-pink-500');

            try {
                await addCartItem({ slug, quantity });
                label.textContent = 'Added to cart';
                button.classList.add('ring-4', 'ring-love-pink-100');

                window.setTimeout(() => {
                    label.textContent = defaultLabel;
                    button.disabled = false;
                    button.classList.remove('scale-[1.02]', 'bg-love-pink-500', 'ring-4', 'ring-love-pink-100');
                }, 1200);
            } catch {
                label.textContent = 'Try again';
                button.disabled = false;
                button.classList.remove('scale-[1.02]', 'bg-love-pink-500', 'ring-4', 'ring-love-pink-100');
            }
        });

        button.dataset.initialized = 'true';
    });
};

const initializeCartPages = () => {
    const formatPeso = (amount) => `\u20B1${amount.toFixed(2)}`;

    document.querySelectorAll('[data-cart-page]').forEach((cartPage) => {
        if (!(cartPage instanceof HTMLElement) || cartPage.dataset.initialized === 'true') {
            return;
        }

        const cartContent = cartPage.querySelector('[data-cart-content]');
        const cartSummary = cartPage.querySelector('[data-cart-summary]');
        const emptyState = cartPage.querySelector('[data-cart-empty]');
        const itemCount = cartPage.querySelector('[data-cart-item-count]');
        const subtotal = cartPage.querySelector('[data-cart-subtotal]');
        const total = cartPage.querySelector('[data-cart-total]');

        const updateCart = () => {
            let subtotalAmount = 0;
            let quantityCount = 0;
            const items = Array.from(cartPage.querySelectorAll('[data-cart-item]')).filter((item) => item instanceof HTMLElement);

            items.forEach((item) => {
                const input = item.querySelector('[data-cart-quantity-input]');
                const lineTotal = item.querySelector('[data-cart-line-total]');
                const price = Number.parseFloat(item.dataset.cartPrice || '0');
                const quantity = input instanceof HTMLInputElement ? Number.parseInt(input.value || '1', 10) : 1;
                const lineAmount = price * quantity;

                quantityCount += quantity;
                subtotalAmount += lineAmount;

                if (lineTotal instanceof HTMLElement) {
                    lineTotal.textContent = formatPeso(lineAmount);
                }
            });

            if (itemCount instanceof HTMLElement) {
                itemCount.textContent = `${quantityCount} ${quantityCount === 1 ? 'item' : 'items'}`;
            }

            if (subtotal instanceof HTMLElement) {
                subtotal.textContent = formatPeso(subtotalAmount);
            }

            if (total instanceof HTMLElement) {
                total.textContent = formatPeso(subtotalAmount);
            }

            setCartNavCount(quantityCount);

            const hasItems = items.length > 0;
            cartContent?.classList.toggle('hidden', !hasItems);
            cartSummary?.classList.toggle('hidden', !hasItems);
            emptyState?.classList.toggle('hidden', hasItems);
        };

        cartPage.querySelectorAll('[data-cart-item]').forEach((item) => {
            if (!(item instanceof HTMLElement)) {
                return;
            }

            const input = item.querySelector('[data-cart-quantity-input]');
            const decrease = item.querySelector('[data-cart-quantity-decrease]');
            const increase = item.querySelector('[data-cart-quantity-increase]');
            const remove = item.querySelector('[data-cart-remove]');

            if (input instanceof HTMLInputElement) {
                const setQuantity = async (value, shouldSync = true) => {
                    const min = Number.parseInt(input.min || '1', 10);
                    const max = Number.parseInt(input.max || '20', 10);
                    const safeValue = Math.min(max, Math.max(min, Number.isNaN(value) ? min : value));

                    input.value = safeValue.toString();

                    if (decrease instanceof HTMLButtonElement) {
                        decrease.disabled = safeValue <= min;
                    }

                    updateCart();

                    if (shouldSync && item.dataset.cartSlug) {
                        await updateCartItem({
                            slug: item.dataset.cartSlug,
                            quantity: safeValue,
                        });
                    }
                };

                decrease?.addEventListener('click', () => {
                    setQuantity(Number.parseInt(input.value || '1', 10) - 1);
                });

                increase?.addEventListener('click', () => {
                    setQuantity(Number.parseInt(input.value || '1', 10) + 1);
                });

                input.addEventListener('input', () => {
                    setQuantity(Number.parseInt(input.value || '1', 10));
                });

                setQuantity(Number.parseInt(input.value || '1', 10), false);
            }

            remove?.addEventListener('click', async () => {
                const slug = item.dataset.cartSlug;

                item.remove();
                updateCart();

                if (slug) {
                    await removeCartItem(slug);
                }
            });
        });

        updateCart();
        cartPage.dataset.initialized = 'true';
    });
};

const initializeCheckoutLoginModal = () => {
    const modal = document.querySelector('[data-checkout-login-modal]');
    const trigger = document.querySelector('[data-checkout-login-trigger]');
    const close = document.querySelector('[data-checkout-login-close]');

    if (!(modal instanceof HTMLElement) || !(trigger instanceof HTMLButtonElement)) {
        return;
    }

    const setOpen = (isOpen) => {
        modal.classList.toggle('hidden', !isOpen);
        modal.classList.toggle('flex', isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
    };

    trigger.addEventListener('click', () => setOpen(true));
    close?.addEventListener('click', () => setOpen(false));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
};

const initializeStorefrontInteractions = () => {
    initializePasswordToggles();
    initializeProductGalleries();
    initializeProductQuantities();
    initializeReviewPagination();
    initializeReviewRatings();
    initializeReviewForms();
    initializeAutoFilterForms();
    initializeContactForms();
    initializeOtpInputs();
    initializeFavoriteToggles();
    initializeAddToCartButtons();
    initializeCartPages();
    initializeCheckoutLoginModal();
    initializeAdminDashboard();
    initializeAdminOrderManagement();
    initializeAdminProducts();
    initializeAdminCustomers();
    initializeAdminNotifications();
    initializeAdminAnalytics();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeStorefrontInteractions);
} else {
    initializeStorefrontInteractions();
}
