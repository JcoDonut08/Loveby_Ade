const basePageButtonClasses = 'inline-flex h-10 min-w-10 items-center justify-center rounded-full px-3 text-sm font-extrabold transition';
const activePageButtonClasses = 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)] hover:bg-love-pink-500';
const inactivePageButtonClasses = 'border border-love-pink-100 text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500';

export const getPagedProducts = (products, state) => {
    const startIndex = (state.page - 1) * state.pageSize;

    return products.slice(startIndex, startIndex + state.pageSize);
};

export const renderProductPagination = ({ section, state, totalRows }) => {
    const pageButtons = section.querySelector('[data-product-page-buttons]');
    const status = section.querySelector('[data-product-pagination-status]');
    const previous = section.querySelector('[data-product-page-previous]');
    const next = section.querySelector('[data-product-page-next]');
    const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize));

    state.page = Math.min(state.page, totalPages);

    const startRow = totalRows === 0 ? 0 : ((state.page - 1) * state.pageSize) + 1;
    const endRow = Math.min(state.page * state.pageSize, totalRows);

    if (status instanceof HTMLElement) {
        status.textContent = `Showing ${startRow}-${endRow} of ${totalRows} products`;
    }

    if (previous instanceof HTMLButtonElement) {
        previous.disabled = state.page <= 1;
    }

    if (next instanceof HTMLButtonElement) {
        next.disabled = state.page >= totalPages;
    }

    if (!(pageButtons instanceof HTMLElement)) {
        return;
    }

    pageButtons.innerHTML = Array.from({ length: totalPages }, (_, index) => {
        const page = index + 1;
        const isActive = page === state.page;

        return `
            <button class="${basePageButtonClasses} ${isActive ? activePageButtonClasses : inactivePageButtonClasses}" type="button" data-product-page-button="${page}" ${isActive ? 'aria-current="page"' : ''}>
                ${page}
            </button>
        `;
    }).join('');
};

export const initializeProductPaginationControls = ({ section, state, renderAll }) => {
    const pageSize = section.querySelector('[data-product-page-size]');
    const previous = section.querySelector('[data-product-page-previous]');
    const next = section.querySelector('[data-product-page-next]');
    const pageButtons = section.querySelector('[data-product-page-buttons]');

    if (pageSize instanceof HTMLSelectElement) {
        pageSize.addEventListener('change', () => {
            state.pageSize = Number.parseInt(pageSize.value || '8', 10);
            state.page = 1;
            renderAll();
        });
    }

    previous?.addEventListener('click', () => {
        state.page = Math.max(1, state.page - 1);
        renderAll();
    });

    next?.addEventListener('click', () => {
        state.page += 1;
        renderAll();
    });

    pageButtons?.addEventListener('click', (event) => {
        const button = event.target instanceof Element ? event.target.closest('[data-product-page-button]') : null;

        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        state.page = Number.parseInt(button.dataset.productPageButton || '1', 10);
        renderAll();
    });
};
