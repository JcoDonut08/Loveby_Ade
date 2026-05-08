const getTotalPages = (totalRows, pageSize) => Math.max(1, Math.ceil(totalRows / pageSize));

export const getPagedOrders = (visibleOrders, state) => {
    const totalPages = getTotalPages(visibleOrders.length, state.pageSize);
    state.page = Math.min(state.page, totalPages);

    const startIndex = (state.page - 1) * state.pageSize;

    return visibleOrders.slice(startIndex, startIndex + state.pageSize);
};

export const renderPagination = ({ section, state, totalRows }) => {
    const paginationStatus = section.querySelector('[data-order-pagination-status]');
    const pageButtons = section.querySelector('[data-order-page-buttons]');
    const previousButton = section.querySelector('[data-order-page-previous]');
    const nextButton = section.querySelector('[data-order-page-next]');
    const totalPages = getTotalPages(totalRows, state.pageSize);
    state.page = Math.min(state.page, totalPages);

    const startRow = totalRows === 0 ? 0 : ((state.page - 1) * state.pageSize) + 1;
    const endRow = Math.min(state.page * state.pageSize, totalRows);

    if (paginationStatus instanceof HTMLElement) {
        paginationStatus.textContent = `Showing ${startRow}-${endRow} of ${totalRows} orders`;
    }

    if (previousButton instanceof HTMLButtonElement) {
        previousButton.disabled = state.page <= 1;
    }

    if (nextButton instanceof HTMLButtonElement) {
        nextButton.disabled = state.page >= totalPages || totalRows === 0;
    }

    if (pageButtons instanceof HTMLElement) {
        pageButtons.innerHTML = Array.from({ length: totalPages }, (_, index) => {
            const page = index + 1;
            const isActive = page === state.page;
            const classes = isActive
                ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)] hover:bg-love-pink-500'
                : 'border border-love-pink-100 text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500';

            return `
                <button class="inline-flex h-10 min-w-10 items-center justify-center rounded-full px-3 text-sm font-extrabold transition ${classes}" type="button" data-order-page="${page}" ${isActive ? 'aria-current="page"' : ''}>
                    ${page}
                </button>
            `;
        }).join('');
    }
};

export const initializePaginationControls = ({ section, state, renderAll }) => {
    const pageSizeControl = section.querySelector('[data-order-page-size]');
    const pageButtons = section.querySelector('[data-order-page-buttons]');
    const previousButton = section.querySelector('[data-order-page-previous]');
    const nextButton = section.querySelector('[data-order-page-next]');

    if (pageSizeControl instanceof HTMLSelectElement) {
        pageSizeControl.addEventListener('change', () => {
            const pageSize = Number.parseInt(pageSizeControl.value || '5', 10);
            state.pageSize = Number.isFinite(pageSize) && pageSize > 0 ? pageSize : 5;
            state.page = 1;
            renderAll();
        });
    }

    previousButton?.addEventListener('click', () => {
        state.page = Math.max(1, state.page - 1);
        renderAll();
    });

    nextButton?.addEventListener('click', () => {
        state.page += 1;
        renderAll();
    });

    pageButtons?.addEventListener('click', (event) => {
        const button = event.target instanceof Element ? event.target.closest('[data-order-page]') : null;

        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        const page = Number.parseInt(button.dataset.orderPage || '1', 10);
        state.page = Number.isFinite(page) && page > 0 ? page : 1;
        renderAll();
    });
};
