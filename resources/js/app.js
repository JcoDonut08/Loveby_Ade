import './bootstrap';
import { initializeAdminAnalytics } from './admin-analytics';
import { initializeAdminCustomers } from './admin-customers';
import { initializeAdminDashboard } from './admin-dashboard';
import { initializeAdminNotifications } from './admin-notifications';
import { initializeAdminOrderManagement } from './admin-orders';
import { initializeAdminOrderModals } from './admin-orders-modals';
import { initializeAdminProducts } from './admin-products';
import { initializeAdminProductModals } from './admin-products-modals';
import { initializeAdminReports } from './admin-reports';
import { initializeCheckoutFlow } from './checkout';
import { initializePromoCarousels } from './promo-carousel';

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

        const reviews = Array.from(section.querySelectorAll('[data-review-page]')).filter((review) => review instanceof HTMLElement);
        const pageButtons = section.querySelectorAll('[data-review-page-button]');
        const filterButtons = section.querySelectorAll('[data-review-filter]');
        const status = section.querySelector('[data-review-pagination-status]');
        const perPage = 5;
        let activeFilter = 'all';

        const matchesFilter = (review) => {
            if (activeFilter === 'all') {
                return true;
            }

            if (activeFilter === 'comments') {
                return review.dataset.reviewHasComment === 'true';
            }

            if (activeFilter === 'media') {
                return review.dataset.reviewHasMedia === 'true';
            }

            if (activeFilter.startsWith('rating:')) {
                return review.dataset.reviewRating === activeFilter.split(':')[1];
            }

            return true;
        };

        const visibleReviews = () => reviews.filter((review) => matchesFilter(review));

        const updateFilterButtons = () => {
            filterButtons.forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.reviewFilter === activeFilter;

                button.classList.toggle('border-love-orange-400', isActive);
                button.classList.toggle('text-love-orange-500', isActive);
                button.classList.toggle('border-slate-200', !isActive);
                button.classList.toggle('text-slate-700', !isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const updatePageButtons = (currentPage, pageCount) => {
            pageButtons.forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const buttonPage = Number.parseInt(button.dataset.reviewPageButton || '1', 10);
                const isVisible = buttonPage <= pageCount;
                const isActive = buttonPage === currentPage;

                button.classList.toggle('hidden', !isVisible);
                button.classList.toggle('border-love-pink-300', isActive);
                button.classList.toggle('bg-love-pink-100', isActive);
                button.classList.toggle('text-love-pink-500', isActive);
                button.classList.toggle('border-slate-200', !isActive);
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('text-slate-600', !isActive);
                button.setAttribute('aria-current', isActive ? 'page' : 'false');
            });
        };

        const showPage = (page) => {
            const filteredReviews = visibleReviews();
            const totalReviews = filteredReviews.length;
            const pageCount = Math.max(1, Math.ceil(totalReviews / perPage));
            const requestedPage = Number.parseInt(page, 10);
            const currentPage = Math.min(Math.max(Number.isNaN(requestedPage) ? 1 : requestedPage, 1), pageCount);
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;

            reviews.forEach((review) => {
                const reviewIndex = filteredReviews.indexOf(review);
                const isVisible = reviewIndex >= startIndex && reviewIndex < endIndex;

                review.classList.toggle('hidden', !isVisible);
            });

            updateFilterButtons();
            updatePageButtons(currentPage, pageCount);

            if (status instanceof HTMLElement) {
                const start = totalReviews === 0 ? 0 : startIndex + 1;
                const end = Math.min(currentPage * perPage, totalReviews);

                status.textContent = `Showing ${start}-${end} of ${totalReviews} reviews`;
            }
        };

        filterButtons.forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.addEventListener('click', () => {
                activeFilter = button.dataset.reviewFilter || 'all';
                showPage('1');
            });
        });

        pageButtons.forEach((button) => {
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

        if (form.hasAttribute('action')) {
            form.dataset.initialized = 'true';

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

            if (input.type !== 'search') {
                input.addEventListener('change', () => submit());
            }
        });

        form.dataset.initialized = 'true';
    });
};

const initializeProductSearchPreviews = () => {
    document.querySelectorAll('[data-product-search-preview-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.searchPreviewInitialized === 'true') {
            return;
        }

        const input = form.querySelector('input[type="search"][name="search"]');
        const grid = document.querySelector('[data-product-results-grid]');
        const emptyState = document.querySelector('[data-product-search-empty]');

        if (!(input instanceof HTMLInputElement) || !(grid instanceof HTMLElement)) {
            return;
        }

        const productResults = Array.from(grid.querySelectorAll('[data-product-result]'))
            .filter((productResult) => productResult instanceof HTMLElement);

        const applySearchPreview = () => {
            const tokens = input.value
                .trim()
                .toLowerCase()
                .split(/\s+/)
                .filter(Boolean);

            let visibleCount = 0;

            productResults.forEach((productResult) => {
                const searchText = productResult.dataset.productSearchText || '';
                const isVisible = tokens.length === 0
                    || tokens.every((token) => searchText.includes(token));

                productResult.classList.toggle('hidden', !isVisible);

                if (isVisible) {
                    visibleCount += 1;
                }
            });

            grid.classList.toggle('hidden', visibleCount === 0);
            emptyState?.classList.toggle('hidden', visibleCount > 0);
        };

        input.addEventListener('input', applySearchPreview);
        applySearchPreview();

        form.dataset.searchPreviewInitialized = 'true';
    });
};

const initializeSearchAutocomplete = () => {
    document.querySelectorAll('[data-search-autocomplete-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || form.dataset.initialized === 'true') {
            return;
        }

        const input = form.querySelector('[data-search-autocomplete-input]');
        const panel = form.querySelector('[data-search-autocomplete-panel]');
        const recentSection = form.querySelector('[data-search-recent-section]');
        const recentList = form.querySelector('[data-search-recent-list]');
        const suggestionSection = form.querySelector('[data-search-suggestion-section]');
        const suggestionList = form.querySelector('[data-search-suggestion-list]');
        const emptyState = form.querySelector('[data-search-empty-state]');
        const suggestionsUrl = form.dataset.searchSuggestionsUrl;
        const recentDestroyUrl = form.dataset.searchRecentDestroyUrl;
        let debounceId;

        if (!(input instanceof HTMLInputElement) || !(panel instanceof HTMLElement) || !suggestionsUrl) {
            return;
        }

        const setOpen = (isOpen) => {
            panel.classList.toggle('hidden', !isOpen);
        };

        const submitSearch = (url) => {
            window.location.href = url;
        };

        const removeRecent = async (title) => {
            if (!recentDestroyUrl) {
                return;
            }

            await window.axios.delete(recentDestroyUrl, {
                data: {
                    term: title,
                },
            });

            await fetchSuggestions();
        };

        const createSearchButton = ({ title, subtitle, url }, label) => {
            const button = document.createElement('button');
            button.className = 'flex w-full items-center justify-between gap-3 px-4 py-3 text-left transition hover:bg-love-pink-100 focus:bg-love-pink-100 focus:outline-none';
            button.type = 'button';

            const text = document.createElement('span');
            text.className = 'min-w-0';

            const titleElement = document.createElement('span');
            titleElement.className = 'block truncate text-sm font-extrabold text-slate-900';
            titleElement.textContent = title;

            const subtitleElement = document.createElement('span');
            subtitleElement.className = 'mt-0.5 block truncate text-xs font-medium text-slate-500';
            subtitleElement.textContent = subtitle || label;

            const action = document.createElement('span');
            action.className = 'shrink-0 text-xs font-bold text-love-pink-500';
            action.textContent = 'Search';

            text.append(titleElement, subtitleElement);
            button.append(text, action);
            button.addEventListener('mousedown', (event) => {
                event.preventDefault();
                input.value = title;
                submitSearch(url);
            });

            return button;
        };

        const createRecentItem = (item, label) => {
            const row = document.createElement('div');
            row.className = 'flex items-center';

            const searchButton = createSearchButton(item, label);
            searchButton.classList.add('min-w-0', 'flex-1');

            const removeButton = document.createElement('button');
            removeButton.className = 'mr-2 flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-lg font-semibold leading-none text-slate-400 transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:bg-love-pink-100 focus:text-love-pink-500 focus:outline-none';
            removeButton.type = 'button';
            removeButton.setAttribute('aria-label', `Remove ${item.title} from recent searches`);
            removeButton.textContent = 'x';
            removeButton.addEventListener('mousedown', (event) => {
                event.preventDefault();
                event.stopPropagation();
                removeRecent(item.title).catch(() => setOpen(false));
            });

            row.append(searchButton, removeButton);

            return row;
        };

        const renderList = (container, section, items, label, createListItem = createSearchButton) => {
            if (!(container instanceof HTMLElement) || !(section instanceof HTMLElement)) {
                return false;
            }

            container.replaceChildren();
            section.classList.toggle('hidden', items.length === 0);

            items.forEach((item) => {
                container.append(createListItem(item, label));
            });

            return items.length > 0;
        };

        const fetchSuggestions = async () => {
            const response = await window.axios.get(suggestionsUrl, {
                params: {
                    q: input.value,
                },
            });

            const hasRecent = input.value.trim()
                ? renderList(recentList, recentSection, [], 'Recent search', createRecentItem)
                : renderList(recentList, recentSection, response.data.recent || [], 'Recent search', createRecentItem);
            const hasSuggestions = renderList(suggestionList, suggestionSection, response.data.suggestions || [], 'Recommended');

            if (emptyState instanceof HTMLElement) {
                emptyState.classList.toggle('hidden', hasRecent || hasSuggestions);
                emptyState.textContent = input.value.trim()
                    ? 'No matching desserts yet.'
                    : 'Start typing to search desserts.';
            }

            setOpen(true);
        };

        input.addEventListener('focus', () => {
            fetchSuggestions().catch(() => setOpen(false));
        });

        input.addEventListener('input', () => {
            window.clearTimeout(debounceId);
            debounceId = window.setTimeout(() => {
                fetchSuggestions().catch(() => setOpen(false));
            }, 180);
        });

        input.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        });

        document.addEventListener('click', (event) => {
            if (!form.contains(event.target)) {
                setOpen(false);
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

const initializeAccountProfilePhotoPreviews = () => {
    document.querySelectorAll('[data-profile-photo-input]').forEach((input) => {
        if (!(input instanceof HTMLInputElement) || input.dataset.initialized === 'true') {
            return;
        }

        let previewUrl;

        input.addEventListener('change', () => {
            const file = input.files?.[0];

            if (!file) {
                return;
            }

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);

            document.querySelectorAll('[data-profile-photo-preview-image]').forEach((image) => {
                if (!(image instanceof HTMLImageElement)) {
                    return;
                }

                image.src = previewUrl;
                image.classList.remove('hidden');
            });

            document.querySelectorAll('[data-profile-photo-preview-fallback]').forEach((fallback) => {
                fallback.classList.add('hidden');
            });
        });

        input.dataset.initialized = 'true';
    });
};

const initializePhilippinePhoneInputs = () => {
    document.querySelectorAll('[data-phone-digits]').forEach((input) => {
        if (!(input instanceof HTMLInputElement) || input.dataset.initialized === 'true') {
            return;
        }

        const normalize = () => {
            input.value = input.value.replace(/\D/g, '').slice(0, 10);
        };

        input.addEventListener('input', normalize);
        normalize();
        input.dataset.initialized = 'true';
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
        const pendingCartSyncs = new Set();
        let cartSyncFailed = false;

        const trackCartSync = (promise) => {
            const trackedPromise = Promise.resolve(promise)
                .catch((error) => {
                    cartSyncFailed = true;

                    throw error;
                })
                .finally(() => {
                    pendingCartSyncs.delete(trackedPromise);
                });

            trackedPromise.catch(() => {});
            pendingCartSyncs.add(trackedPromise);

            return trackedPromise;
        };

        const waitForCartSyncs = async () => {
            while (pendingCartSyncs.size > 0) {
                await Promise.allSettled(Array.from(pendingCartSyncs));
            }

            if (cartSyncFailed) {
                throw new Error('Cart quantity changes were not saved.');
            }
        };

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
                let nextQuantityToSync = null;
                let activeQuantitySync;

                const syncLatestQuantity = () => {
                    const slug = item.dataset.cartSlug;

                    if (!slug) {
                        return Promise.resolve();
                    }

                    nextQuantityToSync = Number.parseInt(input.value || '1', 10);

                    if (activeQuantitySync) {
                        return activeQuantitySync;
                    }

                    activeQuantitySync = (async () => {
                        while (nextQuantityToSync !== null) {
                            const quantity = nextQuantityToSync;
                            nextQuantityToSync = null;

                            await updateCartItem({
                                slug,
                                quantity,
                            });

                            cartSyncFailed = false;
                        }
                    })().finally(() => {
                        activeQuantitySync = null;
                    });

                    return trackCartSync(activeQuantitySync);
                };

                const setQuantity = (value, shouldSync = true) => {
                    const min = Number.parseInt(input.min || '1', 10);
                    const max = Number.parseInt(input.max || '65535', 10);
                    const safeValue = Math.min(max, Math.max(min, Number.isNaN(value) ? min : value));

                    input.value = safeValue.toString();

                    if (decrease instanceof HTMLButtonElement) {
                        decrease.disabled = safeValue <= min;
                    }

                    updateCart();

                    if (shouldSync) {
                        syncLatestQuantity();
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
                    trackCartSync(removeCartItem(slug));
                }
            });
        });

        cartPage.querySelectorAll('[data-cart-checkout-link]').forEach((link) => {
            if (!(link instanceof HTMLAnchorElement)) {
                return;
            }

            link.addEventListener('click', async (event) => {
                if (pendingCartSyncs.size === 0 && !cartSyncFailed) {
                    return;
                }

                event.preventDefault();
                link.setAttribute('aria-busy', 'true');
                link.classList.add('pointer-events-none', 'opacity-70');

                try {
                    await waitForCartSyncs();
                    window.location.href = link.href;
                } catch {
                    link.removeAttribute('aria-busy');
                    link.classList.remove('pointer-events-none', 'opacity-70');
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

const initializeReceiptPrintButtons = () => {
    document.querySelectorAll('[data-print-receipt]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement) || button.dataset.initialized === 'true') {
            return;
        }

        button.addEventListener('click', () => {
            window.print();
        });

        button.dataset.initialized = 'true';
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
    initializeProductSearchPreviews();
    initializeSearchAutocomplete();
    initializeContactForms();
    initializeAccountProfilePhotoPreviews();
    initializePhilippinePhoneInputs();
    initializeOtpInputs();
    initializeFavoriteToggles();
    initializeAddToCartButtons();
    initializeCartPages();
    initializeCheckoutLoginModal();
    initializeReceiptPrintButtons();
    initializeCheckoutFlow();
    initializePromoCarousels();
    initializeAdminDashboard();
    initializeAdminOrderManagement();
    initializeAdminOrderModals();
    initializeAdminProducts();
    initializeAdminProductModals();
    initializeAdminReports();
    initializeAdminCustomers();
    initializeAdminNotifications();
    initializeAdminAnalytics();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeStorefrontInteractions);
} else {
    initializeStorefrontInteractions();
}
