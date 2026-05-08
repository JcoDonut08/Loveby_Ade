import { initializeAddOrderFlow } from './admin-orders-add-order';
import { mockOrders } from './admin-orders-data';
import { getPagedOrders, initializePaginationControls, renderPagination } from './admin-orders-pagination';
import { renderOrderDetails, renderOrderRow, renderToast } from './admin-orders-renderers';

const filterActiveClasses = ['bg-love-pink-400', 'text-white', 'shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]'];
const filterInactiveClasses = ['border', 'border-love-pink-100', 'bg-white', 'text-[#512438]', 'hover:bg-love-pink-100', 'hover:text-love-pink-500'];

const cloneOrders = () => mockOrders.map((order) => ({
    ...order,
    products: order.products.map((product) => ({ ...product })),
}));

export const initializeAdminOrderManagement = () => {
    document.querySelectorAll('[data-admin-order-management]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const orders = cloneOrders();
        const state = {
            filter: 'all',
            search: '',
            page: 1,
            pageSize: 5,
            cancelOrderId: null,
            detailOrderId: null,
        };
        const tableBody = section.querySelector('[data-order-table-body]');
        const emptyState = section.querySelector('[data-order-empty]');
        const resultCount = section.querySelector('[data-order-result-count]');
        const searchInput = document.querySelector('[data-order-search]');
        const cancelModal = section.querySelector('[data-cancel-modal]');
        const cancelInput = section.querySelector('[data-cancel-reason-input]');
        const cancelValidation = section.querySelector('[data-cancel-validation]');
        const detailsDrawer = section.querySelector('[data-order-details]');
        const detailsTitle = section.querySelector('[data-details-title]');
        const detailsContent = section.querySelector('[data-order-details-content]');
        const toastRegion = section.querySelector('[data-order-toast-region]');

        if (!(tableBody instanceof HTMLTableSectionElement)) {
            return;
        }

        const customerConfirmationTimers = new Map();
        const findOrder = (orderId) => orders.find((order) => order.id === orderId);

        const releaseBodyLock = () => {
            const openModal = section.querySelector('[data-add-order-modal]:not(.hidden), [data-cancel-modal]:not(.hidden), [data-order-details]:not(.hidden)');

            if (!openModal) {
                document.body.classList.remove('overflow-hidden');
            }
        };

        const getCounts = () => orders.reduce((counts, order) => {
            counts.all += 1;
            counts[order.status] += 1;

            return counts;
        }, { all: 0, pending: 0, preparing: 0, out_for_delivery: 0, delivered: 0, cancelled: 0 });

        const getVisibleOrders = () => orders.filter((order) => {
            const matchesFilter = state.filter === 'all' || order.status === state.filter;
            const haystack = [
                order.id,
                order.customerName,
                order.contactNumber,
                order.deliveryAddress,
                order.products.map((product) => product.name).join(' '),
            ].join(' ').toLowerCase();

            return matchesFilter && haystack.includes(state.search.toLowerCase());
        });

        const showToast = (message) => {
            if (!(toastRegion instanceof HTMLElement)) {
                return;
            }

            const toast = document.createElement('div');
            toast.className = 'flex translate-y-2 items-start gap-3 rounded-[1.25rem] border border-love-pink-100 bg-white px-4 py-3 text-sm font-extrabold text-[#512438] opacity-0 shadow-[0_22px_55px_-34px_rgba(81,36,56,0.55)] transition duration-300';
            toast.innerHTML = renderToast(message);
            toastRegion.append(toast);

            window.requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            window.setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                window.setTimeout(() => toast.remove(), 300);
            }, 3200);
        };

        const renderCounts = () => {
            Object.entries(getCounts()).forEach(([key, count]) => {
                section.querySelectorAll(`[data-order-summary-count="${key}"], [data-order-filter-count="${key}"]`).forEach((node) => {
                    node.textContent = count.toString();
                });
            });
        };

        const renderFilters = () => {
            section.querySelectorAll('[data-order-filter]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.orderFilter === state.filter;
                filterActiveClasses.forEach((className) => button.classList.toggle(className, isActive));
                filterInactiveClasses.forEach((className) => button.classList.toggle(className, !isActive));
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const renderTable = () => {
            const visibleOrders = getVisibleOrders();
            const pagedOrders = getPagedOrders(visibleOrders, state);
            tableBody.innerHTML = pagedOrders.map(renderOrderRow).join('');

            if (emptyState instanceof HTMLElement) {
                emptyState.classList.toggle('hidden', visibleOrders.length > 0);
            }

            if (resultCount instanceof HTMLElement) {
                resultCount.textContent = `${visibleOrders.length} of ${orders.length} mock orders shown`;
            }
        };

        const renderAll = () => {
            renderCounts();
            renderFilters();
            renderTable();
            renderPagination({ section, state, totalRows: getVisibleOrders().length });
        };

        const openDetails = (orderId) => {
            const order = findOrder(orderId);

            if (!order || !(detailsDrawer instanceof HTMLElement) || !(detailsContent instanceof HTMLElement)) {
                return;
            }

            state.detailOrderId = orderId;

            if (detailsTitle instanceof HTMLElement) {
                detailsTitle.textContent = order.id;
            }

            detailsContent.innerHTML = renderOrderDetails(order);
            detailsDrawer.classList.remove('hidden');
            detailsDrawer.classList.add('flex');
            detailsDrawer.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const updateStatus = (orderId, status, message) => {
            const order = findOrder(orderId);

            if (!order) {
                return;
            }

            order.status = status;
            renderAll();

            if (state.detailOrderId === order.id) {
                openDetails(order.id);
            }

            showToast(message);
        };

        const scheduleCustomerDeliveryConfirmation = (orderId) => {
            if (customerConfirmationTimers.has(orderId)) {
                window.clearTimeout(customerConfirmationTimers.get(orderId));
            }

            const timer = window.setTimeout(() => {
                customerConfirmationTimers.delete(orderId);

                const order = findOrder(orderId);

                if (!order || order.status !== 'out_for_delivery') {
                    return;
                }

                updateStatus(orderId, 'delivered', 'Customer confirmed delivery. Status changed to Delivered.');
            }, 5000);

            customerConfirmationTimers.set(orderId, timer);
        };

        const closeCancelModal = () => {
            if (cancelModal instanceof HTMLElement) {
                cancelModal.classList.add('hidden');
                cancelModal.classList.remove('flex');
                cancelModal.setAttribute('aria-hidden', 'true');
            }

            state.cancelOrderId = null;
            releaseBodyLock();
        };

        const openCancelModal = (orderId) => {
            state.cancelOrderId = orderId;

            if (cancelInput instanceof HTMLTextAreaElement) {
                cancelInput.value = '';
                cancelInput.focus();
            }

            if (cancelValidation instanceof HTMLElement) {
                cancelValidation.classList.add('hidden');
            }

            if (cancelModal instanceof HTMLElement) {
                cancelModal.classList.remove('hidden');
                cancelModal.classList.add('flex');
                cancelModal.setAttribute('aria-hidden', 'false');
            }

            document.body.classList.add('overflow-hidden');
        };

        const closeDetails = () => {
            if (detailsDrawer instanceof HTMLElement) {
                detailsDrawer.classList.add('hidden');
                detailsDrawer.classList.remove('flex');
                detailsDrawer.setAttribute('aria-hidden', 'true');
            }

            state.detailOrderId = null;
            releaseBodyLock();
        };

        section.querySelectorAll('[data-order-filter]').forEach((button) => {
            if (button instanceof HTMLButtonElement) {
                button.addEventListener('click', () => {
                    state.filter = button.dataset.orderFilter || 'all';
                    state.page = 1;
                    renderAll();
                });
            }
        });

        if (searchInput instanceof HTMLInputElement) {
            searchInput.addEventListener('input', () => {
                state.search = searchInput.value.trim();
                state.page = 1;
                renderAll();
            });
        }

        const { closeAddOrderModal } = initializeAddOrderFlow({
            section,
            orders,
            state,
            renderAll,
            showToast,
            releaseBodyLock,
            clearSearch: () => {
                if (searchInput instanceof HTMLInputElement) {
                    searchInput.value = '';
                }
            },
        });

        initializePaginationControls({ section, state, renderAll });

        tableBody.addEventListener('click', (event) => {
            const button = event.target instanceof Element ? event.target.closest('[data-order-action]') : null;

            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const orderId = button.dataset.orderId || '';
            const action = button.dataset.orderAction;

            if (action === 'confirm') {
                updateStatus(orderId, 'preparing', 'Order confirmed. Status changed to Preparing.');
            } else if (action === 'cancel') {
                openCancelModal(orderId);
            } else if (action === 'ship') {
                updateStatus(orderId, 'out_for_delivery', 'Order is now out for delivery. Waiting for customer confirmation.');
                scheduleCustomerDeliveryConfirmation(orderId);
            } else if (action === 'view') {
                openDetails(orderId);
            }
        });

        section.querySelectorAll('[data-cancel-close]').forEach((button) => {
            button.addEventListener('click', closeCancelModal);
        });

        section.querySelectorAll('[data-cancel-reason]').forEach((button) => {
            button.addEventListener('click', () => {
                if (cancelInput instanceof HTMLTextAreaElement && button instanceof HTMLButtonElement) {
                    cancelInput.value = button.dataset.cancelReason || '';
                    cancelInput.focus();
                }

                if (cancelValidation instanceof HTMLElement) {
                    cancelValidation.classList.add('hidden');
                }
            });
        });

        section.querySelector('[data-cancel-confirm]')?.addEventListener('click', () => {
            const reason = cancelInput instanceof HTMLTextAreaElement ? cancelInput.value.trim() : '';
            const order = state.cancelOrderId ? findOrder(state.cancelOrderId) : null;

            if (!reason) {
                cancelValidation?.classList.remove('hidden');

                return;
            }

            if (order) {
                order.status = 'cancelled';
                order.cancellationReason = reason;
                renderAll();
                closeCancelModal();
                showToast('Order has been cancelled.');
            }
        });

        section.querySelectorAll('[data-details-close]').forEach((button) => {
            button.addEventListener('click', closeDetails);
        });

        detailsContent?.addEventListener('click', (event) => {
            const button = event.target instanceof Element ? event.target.closest('[data-details-show-more]') : null;

            if (!(button instanceof HTMLButtonElement) || !(detailsContent instanceof HTMLElement)) {
                return;
            }

            detailsContent.querySelectorAll('[data-details-extra-product]').forEach((product) => {
                product.classList.remove('hidden');
                product.classList.add('flex');
            });

            button.remove();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAddOrderModal();
                closeCancelModal();
                closeDetails();
            }
        });

        renderAll();
        section.dataset.initialized = 'true';
    });
};
