const pageButtonClasses = 'inline-flex h-10 min-w-10 items-center justify-center rounded-full px-3 text-sm font-extrabold transition';
const activePageButtonClasses = 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)] hover:bg-love-pink-500';
const inactivePageButtonClasses = 'border border-love-pink-100 text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500';

const getTotalPages = (totalRows, pageSize) => Math.max(1, Math.ceil(totalRows / pageSize));

const renderNotificationPagination = ({ section, rows, state }) => {
    const status = section.querySelector('[data-notification-pagination-status]');
    const pageButtons = section.querySelector('[data-notification-page-buttons]');
    const previous = section.querySelector('[data-notification-page-previous]');
    const next = section.querySelector('[data-notification-page-next]');
    const totalPages = getTotalPages(rows.length, state.pageSize);

    state.page = Math.min(state.page, totalPages);

    const startRow = rows.length === 0 ? 0 : ((state.page - 1) * state.pageSize) + 1;
    const endRow = Math.min(state.page * state.pageSize, rows.length);

    rows.forEach((row, index) => {
        row.classList.toggle('hidden', index < startRow - 1 || index >= endRow);
    });

    if (status instanceof HTMLElement) {
        status.textContent = `Showing ${startRow}-${endRow} of ${rows.length} notifications`;
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
            <button class="${pageButtonClasses} ${isActive ? activePageButtonClasses : inactivePageButtonClasses}" type="button" data-notification-page="${page}" ${isActive ? 'aria-current="page"' : ''}>
                ${page}
            </button>
        `;
    }).join('');
};

export const initializeAdminNotifications = () => {
    document.querySelectorAll('[data-admin-notifications]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const rows = Array.from(section.querySelectorAll('[data-notification-row]')).filter((row) => row instanceof HTMLElement);
        const pageSize = section.querySelector('[data-notification-page-size]');
        const pageButtons = section.querySelector('[data-notification-page-buttons]');
        const previous = section.querySelector('[data-notification-page-previous]');
        const next = section.querySelector('[data-notification-page-next]');
        const state = {
            page: 1,
            pageSize: pageSize instanceof HTMLSelectElement ? Number.parseInt(pageSize.value || '6', 10) : 6,
        };

        const renderAll = () => renderNotificationPagination({ section, rows, state });

        if (pageSize instanceof HTMLSelectElement) {
            pageSize.addEventListener('change', () => {
                const nextPageSize = Number.parseInt(pageSize.value || '6', 10);
                state.pageSize = Number.isFinite(nextPageSize) && nextPageSize > 0 ? nextPageSize : 6;
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
            const button = event.target instanceof Element ? event.target.closest('[data-notification-page]') : null;

            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const page = Number.parseInt(button.dataset.notificationPage || '1', 10);
            state.page = Number.isFinite(page) && page > 0 ? page : 1;
            renderAll();
        });

        renderAll();
        section.dataset.initialized = 'true';
    });
};
