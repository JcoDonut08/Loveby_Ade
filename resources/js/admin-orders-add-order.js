import { productCatalog } from './admin-orders-data';
import { escapeHtml } from './admin-orders-renderers';

const dateFormatter = new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
});

const padDatePart = (value) => value.toString().padStart(2, '0');

const getDateInputValue = (date = new Date()) => [
    date.getFullYear(),
    padDatePart(date.getMonth() + 1),
    padDatePart(date.getDate()),
].join('-') + `T${padDatePart(date.getHours())}:${padDatePart(date.getMinutes())}`;

const getDisplayDate = (dateInputValue) => {
    const date = new Date(dateInputValue);

    return Number.isNaN(date.getTime()) ? dateFormatter.format(new Date()) : dateFormatter.format(date);
};

export const initializeAddOrderFlow = ({
    section,
    orders,
    state,
    renderAll,
    showToast,
    clearSearch,
    releaseBodyLock,
}) => {
    const addOrderButton = section.querySelector('[data-add-order]');
    const addOrderModal = section.querySelector('[data-add-order-modal]');
    const addOrderForm = section.querySelector('[data-add-order-form]');
    const addOrderIdInput = section.querySelector('[data-add-order-id]');
    const addCustomerNameInput = section.querySelector('[data-add-customer-name]');
    const addProductInput = section.querySelector('[data-add-product]');
    const addQuantityInput = section.querySelector('[data-add-quantity]');
    const addTotalInput = section.querySelector('[data-add-total]');
    const addDateInput = section.querySelector('[data-add-date]');
    const addOrderValidation = section.querySelector('[data-add-order-validation]');

    const findProduct = (name) => productCatalog.find((product) => product.name === name) || productCatalog[0];

    const getNextOrderId = () => {
        const nextNumber = orders.reduce((highest, order) => {
            const [, number] = order.id.match(/#LBA-(\d+)/) || [];
            const parsedNumber = Number.parseInt(number || '0', 10);

            return Number.isFinite(parsedNumber) ? Math.max(highest, parsedNumber) : highest;
        }, 3500) + 1;

        return `#LBA-${nextNumber}`;
    };

    const updateAddOrderTotal = () => {
        if (!(addProductInput instanceof HTMLSelectElement) || !(addQuantityInput instanceof HTMLInputElement) || !(addTotalInput instanceof HTMLInputElement)) {
            return;
        }

        const product = findProduct(addProductInput.value);
        const quantity = Math.max(1, Number.parseInt(addQuantityInput.value || '1', 10));
        addQuantityInput.value = quantity.toString();
        addTotalInput.value = (product.price * quantity).toFixed(2);
    };

    const resetAddOrderForm = () => {
        if (addOrderForm instanceof HTMLFormElement) {
            addOrderForm.reset();
        }

        if (addOrderIdInput instanceof HTMLInputElement) {
            addOrderIdInput.value = getNextOrderId();
        }

        if (addProductInput instanceof HTMLSelectElement && productCatalog[0]) {
            addProductInput.value = productCatalog[0].name;
        }

        if (addQuantityInput instanceof HTMLInputElement) {
            addQuantityInput.value = '1';
        }

        if (addDateInput instanceof HTMLInputElement) {
            addDateInput.value = getDateInputValue();
        }

        addOrderValidation?.classList.add('hidden');
        updateAddOrderTotal();
    };

    const openAddOrderModal = () => {
        resetAddOrderForm();

        if (addOrderModal instanceof HTMLElement) {
            addOrderModal.classList.remove('hidden');
            addOrderModal.classList.add('flex');
            addOrderModal.setAttribute('aria-hidden', 'false');
        }

        if (addCustomerNameInput instanceof HTMLInputElement) {
            addCustomerNameInput.focus();
        }

        document.body.classList.add('overflow-hidden');
    };

    const closeAddOrderModal = () => {
        if (addOrderModal instanceof HTMLElement) {
            addOrderModal.classList.add('hidden');
            addOrderModal.classList.remove('flex');
            addOrderModal.setAttribute('aria-hidden', 'true');
        }

        releaseBodyLock();
    };

    const populateProductOptions = () => {
        if (!(addProductInput instanceof HTMLSelectElement)) {
            return;
        }

        addProductInput.innerHTML = productCatalog.map((product) => `
            <option value="${escapeHtml(product.name)}">${escapeHtml(product.name)}</option>
        `).join('');
    };

    addOrderButton?.addEventListener('click', openAddOrderModal);
    section.querySelectorAll('[data-add-order-close]').forEach((button) => {
        button.addEventListener('click', closeAddOrderModal);
    });
    addProductInput?.addEventListener('change', updateAddOrderTotal);
    addQuantityInput?.addEventListener('input', updateAddOrderTotal);

    addOrderForm?.addEventListener('submit', (event) => {
        event.preventDefault();

        const orderId = addOrderIdInput instanceof HTMLInputElement ? addOrderIdInput.value : getNextOrderId();
        const customerName = addCustomerNameInput instanceof HTMLInputElement ? addCustomerNameInput.value.trim() : '';
        const productName = addProductInput instanceof HTMLSelectElement ? addProductInput.value : '';
        const quantity = addQuantityInput instanceof HTMLInputElement ? Number.parseInt(addQuantityInput.value || '1', 10) : 1;
        const totalAmount = addTotalInput instanceof HTMLInputElement ? Number.parseFloat(addTotalInput.value || '0') : 0;
        const dateOrdered = addDateInput instanceof HTMLInputElement ? addDateInput.value : getDateInputValue();
        const product = findProduct(productName);

        if (!customerName || !product || !Number.isFinite(quantity) || quantity < 1 || !Number.isFinite(totalAmount) || totalAmount < 0 || !dateOrdered) {
            addOrderValidation?.classList.remove('hidden');

            return;
        }

        orders.unshift({
            id: orderId,
            customerName,
            contactNumber: 'Walk-in customer',
            deliveryAddress: 'In-store purchase',
            dateOrdered: getDisplayDate(dateOrdered),
            status: 'pending',
            customerNote: 'Walk-in order recorded by admin.',
            adminNote: '',
            cancellationReason: '',
            totalAmount,
            products: [{
                name: product.name,
                quantity,
                price: product.price,
                image: product.image,
            }],
        });

        state.filter = 'all';
        state.search = '';
        state.page = 1;
        clearSearch();
        renderAll();
        closeAddOrderModal();
        showToast('Walk-in order added. Status set to Pending.');
    });

    populateProductOptions();

    return { closeAddOrderModal };
};
