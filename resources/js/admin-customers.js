import { mockCustomers } from './admin-customers-data';
import { getPagedCustomers, initializeCustomerPaginationControls, renderCustomerPagination } from './admin-customers-pagination';
import { getCustomerSpend, renderCustomerCard, renderCustomerDetails } from './admin-customers-renderers';

const filterActiveClasses = ['bg-love-pink-400', 'text-white', 'shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]'];
const filterInactiveClasses = ['border', 'border-love-pink-100', 'bg-love-cream', 'text-[#512438]', 'hover:bg-love-pink-100', 'hover:text-love-pink-500'];

const cloneCustomers = () => mockCustomers.map((customer) => ({
    ...customer,
    orders: customer.orders.map((order) => ({ ...order })),
    activity: [...customer.activity],
}));

export const initializeAdminCustomers = () => {
    document.querySelectorAll('[data-admin-customers]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const customers = cloneCustomers();
        const state = {
            filter: 'all',
            search: '',
            page: 1,
            pageSize: 3,
        };

        const list = section.querySelector('[data-customer-list]');
        const emptyState = section.querySelector('[data-customer-empty]');
        const resultCount = section.querySelector('[data-customer-result-count]');
        const searchInput = section.querySelector('[data-customer-search]');
        const globalSearchInput = document.querySelector('[data-customer-global-search]');
        const detailsModal = section.querySelector('[data-customer-details]');
        const detailsTitle = section.querySelector('[data-customer-details-title]');
        const detailsContent = section.querySelector('[data-customer-details-content]');

        if (!(list instanceof HTMLElement)) {
            return;
        }

        const findCustomer = (customerId) => customers.find((customer) => customer.id === customerId);

        const isActiveToday = (customer) => ['today', 'minutes', 'hours'].some((term) => customer.lastActive.toLowerCase().includes(term));

        const getCounts = () => customers.reduce((counts, customer) => {
            counts.all += 1;

            if (customer.segment === 'top_spender' || customer.segment === 'new_customer') {
                counts[customer.segment] += 1;
            }

            if (isActiveToday(customer)) {
                counts.active_today += 1;
            }

            return counts;
        }, { all: 0, top_spender: 0, active_today: 0, new_customer: 0 });

        const getVisibleCustomers = () => customers.filter((customer) => {
            const matchesFilter = state.filter === 'all'
                || customer.segment === state.filter
                || (state.filter === 'active_today' && isActiveToday(customer));
            const haystack = [
                customer.id,
                customer.name,
                customer.email,
                customer.phone,
                customer.segment,
                customer.orders.map((order) => order.items).join(' '),
            ].join(' ').toLowerCase();

            return matchesFilter && haystack.includes(state.search.toLowerCase());
        }).sort((first, second) => {
            if (state.filter === 'top_spender') {
                return getCustomerSpend(second) - getCustomerSpend(first);
            }

            return 0;
        });

        const renderCounts = () => {
            const counts = getCounts();

            section.querySelectorAll('[data-customer-summary-count], [data-customer-filter-count]').forEach((node) => {
                const key = node.dataset.customerSummaryCount || node.dataset.customerFilterCount || 'all';
                node.textContent = (counts[key] || 0).toString();
            });
        };

        const renderFilters = () => {
            section.querySelectorAll('[data-customer-filter]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.customerFilter === state.filter;
                filterActiveClasses.forEach((className) => button.classList.toggle(className, isActive));
                filterInactiveClasses.forEach((className) => button.classList.toggle(className, !isActive));
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const renderList = () => {
            const visibleCustomers = getVisibleCustomers();
            const pagedCustomers = getPagedCustomers(visibleCustomers, state);

            list.innerHTML = pagedCustomers.map(renderCustomerCard).join('');

            if (emptyState instanceof HTMLElement) {
                emptyState.classList.toggle('hidden', visibleCustomers.length > 0);
            }

            if (resultCount instanceof HTMLElement) {
                resultCount.textContent = `${visibleCustomers.length} of ${customers.length} mock customers shown`;
            }
        };

        const renderAll = () => {
            const totalRows = getVisibleCustomers().length;

            renderCounts();
            renderFilters();
            renderList();
            renderCustomerPagination({ section, state, totalRows });
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

        const closeDetails = () => {
            if (detailsModal instanceof HTMLElement) {
                detailsModal.classList.add('hidden');
                detailsModal.classList.remove('flex');
                detailsModal.setAttribute('aria-hidden', 'true');
            }

            document.body.classList.remove('overflow-hidden');
        };

        const openDetails = (customerId) => {
            const customer = findCustomer(customerId);

            if (!customer || !(detailsModal instanceof HTMLElement) || !(detailsContent instanceof HTMLElement)) {
                return;
            }

            if (detailsTitle instanceof HTMLElement) {
                detailsTitle.textContent = customer.name;
            }

            detailsContent.innerHTML = renderCustomerDetails(customer);
            detailsModal.classList.remove('hidden');
            detailsModal.classList.add('flex');
            detailsModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        section.querySelectorAll('[data-customer-filter]').forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.addEventListener('click', () => {
                state.filter = button.dataset.customerFilter || 'all';
                state.page = 1;
                renderAll();
            });
        });

        if (searchInput instanceof HTMLInputElement) {
            searchInput.addEventListener('input', () => setSearch(searchInput.value, searchInput));
        }

        if (globalSearchInput instanceof HTMLInputElement) {
            globalSearchInput.addEventListener('input', () => setSearch(globalSearchInput.value, globalSearchInput));
        }

        list.addEventListener('click', (event) => {
            const button = event.target instanceof Element ? event.target.closest('[data-customer-action="view"]') : null;

            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            openDetails(button.dataset.customerId || '');
        });

        section.querySelectorAll('[data-customer-details-close]').forEach((button) => {
            button.addEventListener('click', closeDetails);
        });

        initializeCustomerPaginationControls({ section, state, renderAll });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeDetails();
            }
        });

        renderAll();
        section.dataset.initialized = 'true';
    });
};
