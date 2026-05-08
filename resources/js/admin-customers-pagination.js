const basePageButtonClasses = 'inline-flex h-10 min-w-10 items-center justify-center rounded-full px-3 text-sm font-extrabold transition';
const activePageButtonClasses = 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)] hover:bg-love-pink-500';
const inactivePageButtonClasses = 'border border-love-pink-100 text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500';

export const getPagedCustomers = (customers, state) => {
    const startIndex = (state.page - 1) * state.pageSize;

    return customers.slice(startIndex, startIndex + state.pageSize);
};

export const renderCustomerPagination = ({ section, state, totalRows }) => {
    const pageButtons = section.querySelector('[data-customer-page-buttons]');
    const status = section.querySelector('[data-customer-pagination-status]');
    const previous = section.querySelector('[data-customer-page-previous]');
    const next = section.querySelector('[data-customer-page-next]');
    const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize));

    state.page = Math.min(state.page, totalPages);

    const startRow = totalRows === 0 ? 0 : ((state.page - 1) * state.pageSize) + 1;
    const endRow = Math.min(state.page * state.pageSize, totalRows);

    if (status instanceof HTMLElement) {
        status.textContent = `Showing ${startRow}-${endRow} of ${totalRows} customers`;
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
            <button class="${basePageButtonClasses} ${isActive ? activePageButtonClasses : inactivePageButtonClasses}" type="button" data-customer-page-button="${page}" ${isActive ? 'aria-current="page"' : ''}>
                ${page}
            </button>
        `;
    }).join('');
};

export const initializeCustomerPaginationControls = ({ section, state, renderAll }) => {
    const pageSize = section.querySelector('[data-customer-page-size]');
    const previous = section.querySelector('[data-customer-page-previous]');
    const next = section.querySelector('[data-customer-page-next]');
    const pageButtons = section.querySelector('[data-customer-page-buttons]');

    if (pageSize instanceof HTMLSelectElement) {
        pageSize.addEventListener('change', () => {
            state.pageSize = Number.parseInt(pageSize.value || '3', 10);
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
        const button = event.target instanceof Element ? event.target.closest('[data-customer-page-button]') : null;

        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        state.page = Number.parseInt(button.dataset.customerPageButton || '1', 10);
        renderAll();
    });
};
