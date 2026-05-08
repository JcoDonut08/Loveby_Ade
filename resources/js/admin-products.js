import { fallbackProductImage, mockProducts } from './admin-products-data';
import { getPagedProducts, initializeProductPaginationControls, renderProductPagination } from './admin-products-pagination';
import { escapeHtml, renderProductCard, renderProductToast } from './admin-products-renderers';

const filterActiveClasses = ['bg-love-pink-400', 'text-white', 'shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]'];
const filterInactiveClasses = ['border', 'border-love-pink-100', 'bg-love-cream', 'text-[#512438]', 'hover:bg-love-pink-100', 'hover:text-love-pink-500'];

const cloneProducts = () => mockProducts.map((product) => ({
    ...product,
    images: product.images.map((image) => ({ ...image })),
}));

const getNumericValue = (value, fallback = 0) => {
    const parsedValue = Number.parseFloat(value);

    return Number.isFinite(parsedValue) ? parsedValue : fallback;
};

const getUploadedImages = (files) => Array.from(files)
    .filter((file) => file.type.startsWith('image/'))
    .map((file) => ({
        src: URL.createObjectURL(file),
        alt: file.name,
        name: file.name,
    }));

const renderImagePreview = (preview, images) => {
    if (!(preview instanceof HTMLElement)) {
        return;
    }

    if (images.length === 0) {
        preview.innerHTML = `
            <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-4 text-sm font-bold text-[#9a6c7b] sm:col-span-4">
                No image selected yet.
            </div>
        `;

        return;
    }

    preview.innerHTML = images.map((image, index) => `
        <figure class="relative overflow-hidden rounded-[1rem] border border-love-pink-100 bg-white shadow-sm">
            <img class="aspect-square w-full object-cover" src="${escapeHtml(image.src)}" alt="${escapeHtml(image.alt || image.name || 'Product image preview')}">
            ${index === 0 ? '<figcaption class="absolute bottom-2 left-2 rounded-full bg-white/92 px-2.5 py-1 text-xs font-extrabold text-[#512438] shadow-sm backdrop-blur-sm">Primary</figcaption>' : ''}
        </figure>
    `).join('');
};

export const initializeAdminProducts = () => {
    document.querySelectorAll('[data-admin-products]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const products = cloneProducts();
        const state = {
            filter: 'all',
            search: '',
            page: 1,
            pageSize: 8,
            editingProductId: null,
            imageDraft: [],
        };

        const grid = section.querySelector('[data-product-grid]');
        const emptyState = section.querySelector('[data-product-empty]');
        const searchInput = section.querySelector('[data-product-search]');
        const globalSearchInput = document.querySelector('[data-product-global-search]');
        const modal = section.querySelector('[data-product-modal]');
        const form = section.querySelector('[data-product-form]');
        const modalTitle = section.querySelector('[data-product-modal-title]');
        const validation = section.querySelector('[data-product-validation]');
        const toastRegion = section.querySelector('[data-product-toast-region]');
        const imagePreview = section.querySelector('[data-product-image-preview]');

        if (!(grid instanceof HTMLElement) || !(form instanceof HTMLFormElement)) {
            return;
        }

        const fields = {
            id: form.querySelector('[data-product-id]'),
            images: form.querySelector('[data-product-images]'),
            name: form.querySelector('[data-product-name]'),
            description: form.querySelector('[data-product-description]'),
            category: form.querySelector('[data-product-category]'),
            price: form.querySelector('[data-product-price]'),
            stock: form.querySelector('[data-product-stock]'),
        };

        if (!Object.values(fields).every((field) => field instanceof HTMLElement)) {
            return;
        }

        const findProduct = (productId) => products.find((product) => product.id === productId);

        const getNextProductId = () => {
            const highestId = products.reduce((highest, product) => {
                const match = product.id.match(/(\d+)$/);
                const productNumber = match ? Number.parseInt(match[1], 10) : highest;

                return Number.isFinite(productNumber) ? Math.max(highest, productNumber) : highest;
            }, 1000);

            return `PRD-${highestId + 1}`;
        };

        const clearSearchInputs = () => {
            state.search = '';

            if (searchInput instanceof HTMLInputElement) {
                searchInput.value = '';
            }

            if (globalSearchInput instanceof HTMLInputElement) {
                globalSearchInput.value = '';
            }
        };

        const getVisibleProducts = () => products.filter((product) => {
            const matchesFilter = state.filter === 'all' || product.category.toLowerCase() === state.filter;
            const haystack = [
                product.id,
                product.name,
                product.description,
                product.category,
                product.price,
                product.stock,
            ].join(' ').toLowerCase();

            return matchesFilter && haystack.includes(state.search.toLowerCase());
        });

        const showToast = (message) => {
            if (!(toastRegion instanceof HTMLElement)) {
                return;
            }

            const toast = document.createElement('div');
            toast.className = 'flex translate-y-2 items-start gap-3 rounded-[1.25rem] border border-love-pink-100 bg-white px-4 py-3 text-sm font-extrabold text-[#512438] opacity-0 shadow-[0_22px_55px_-34px_rgba(81,36,56,0.55)] transition duration-300';
            toast.innerHTML = renderProductToast(message);
            toastRegion.append(toast);

            window.requestAnimationFrame(() => toast.classList.remove('translate-y-2', 'opacity-0'));

            window.setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                window.setTimeout(() => toast.remove(), 300);
            }, 3200);
        };

        const renderFilters = () => {
            section.querySelectorAll('[data-product-filter]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.productFilter === state.filter;
                filterActiveClasses.forEach((className) => button.classList.toggle(className, isActive));
                filterInactiveClasses.forEach((className) => button.classList.toggle(className, !isActive));
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const renderGrid = () => {
            const visibleProducts = getVisibleProducts();
            const pagedProducts = getPagedProducts(visibleProducts, state);

            grid.innerHTML = pagedProducts.map(renderProductCard).join('');

            if (emptyState instanceof HTMLElement) {
                emptyState.classList.toggle('hidden', visibleProducts.length > 0);
            }
        };

        const renderAll = () => {
            const totalRows = getVisibleProducts().length;

            renderFilters();
            renderGrid();
            renderProductPagination({ section, state, totalRows });
        };

        const setSearch = (value, sourceInput) => {
            state.search = value.trim();
            state.page = 1;

            if (searchInput instanceof HTMLInputElement && sourceInput !== searchInput) {
                searchInput.value = value;
            }

            if (globalSearchInput instanceof HTMLInputElement && sourceInput !== globalSearchInput) {
                globalSearchInput.value = value;
            }

            renderAll();
        };

        const closeModal = () => {
            if (modal instanceof HTMLElement) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
            }

            state.editingProductId = null;
            state.imageDraft = [];
            document.body.classList.remove('overflow-hidden');
        };

        const setFieldValues = (product) => {
            fields.id.value = product?.id || '';
            fields.name.value = product?.name || '';
            fields.description.value = product?.description || '';
            fields.category.value = product?.category || 'Cakes';
            fields.price.value = product ? product.price.toString() : '120';
            fields.stock.value = product ? product.stock.toString() : '12';
            fields.images.value = '';
            state.imageDraft = product?.images?.map((image) => ({ ...image })) || [];
            renderImagePreview(imagePreview, state.imageDraft);
        };

        const openModal = (product = null) => {
            state.editingProductId = product?.id || null;
            form.reset();
            setFieldValues(product);

            if (modalTitle instanceof HTMLElement) {
                modalTitle.textContent = product ? 'Edit product' : 'Add product';
            }

            validation?.classList.add('hidden');

            if (modal instanceof HTMLElement) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
            }

            document.body.classList.add('overflow-hidden');
            fields.name.focus();
        };

        const getProductFromForm = () => ({
            id: state.editingProductId || getNextProductId(),
            name: fields.name.value.trim(),
            description: fields.description.value.trim(),
            category: fields.category.value,
            price: Math.max(0, getNumericValue(fields.price.value)),
            stock: Math.max(0, Number.parseInt(fields.stock.value || '0', 10)),
            images: state.imageDraft.length > 0 ? state.imageDraft.map((image) => ({
                src: image.src,
                alt: image.alt || image.name || fields.name.value.trim(),
            })) : [{ ...fallbackProductImage }],
        });

        section.querySelector('[data-product-open-add]')?.addEventListener('click', () => openModal());

        section.querySelectorAll('[data-product-modal-close]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        section.querySelectorAll('[data-product-filter]').forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.addEventListener('click', () => {
                state.filter = button.dataset.productFilter || 'all';
                state.page = 1;
                renderAll();
            });
        });

        fields.images.addEventListener('change', () => {
            state.imageDraft = getUploadedImages(fields.images.files || []);
            renderImagePreview(imagePreview, state.imageDraft);
        });

        if (searchInput instanceof HTMLInputElement) {
            searchInput.addEventListener('input', () => setSearch(searchInput.value, searchInput));
        }

        if (globalSearchInput instanceof HTMLInputElement) {
            globalSearchInput.addEventListener('input', () => setSearch(globalSearchInput.value, globalSearchInput));
        }

        grid.addEventListener('click', (event) => {
            const button = event.target instanceof Element ? event.target.closest('[data-product-action]') : null;

            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const product = findProduct(button.dataset.productId || '');

            if (!product) {
                return;
            }

            if (button.dataset.productAction === 'edit') {
                openModal(product);
            } else if (button.dataset.productAction === 'delete') {
                products.splice(products.indexOf(product), 1);
                renderAll();
                showToast('Product removed from catalog.');
            }
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!form.reportValidity()) {
                validation?.classList.remove('hidden');

                return;
            }

            const formProduct = getProductFromForm();
            const existingProduct = state.editingProductId ? findProduct(state.editingProductId) : null;

            if (existingProduct) {
                Object.assign(existingProduct, formProduct);
                closeModal();
                renderAll();
                showToast('Product updated.');

                return;
            }

            products.unshift(formProduct);
            state.filter = 'all';
            state.page = 1;
            clearSearchInputs();
            closeModal();
            renderAll();
            showToast('Product added to catalog.');
        });

        initializeProductPaginationControls({ section, state, renderAll });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        renderAll();
        section.dataset.initialized = 'true';
    });
};
