import { orderStatuses } from './admin-orders-data';
import { orderIcons, statusIcons } from './admin-orders-icons';

const moneyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    maximumFractionDigits: 2,
});

const actionStyles = {
    confirm: 'border-emerald-100 bg-emerald-50 text-emerald-600 hover:bg-emerald-100',
    cancel: 'border-rose-100 bg-rose-50 text-rose-500 hover:bg-rose-100',
    ship: 'border-love-blue-100 bg-love-blue-100/80 text-[#23445c] hover:bg-love-blue-200',
    view: 'border-love-pink-100 bg-white text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500',
};

const tooltipInteractionClasses = {
    action: 'group-hover/action:translate-y-0 group-hover/action:opacity-100 group-focus-visible/action:translate-y-0 group-focus-visible/action:opacity-100',
    status: 'group-hover/status:translate-y-0 group-hover/status:opacity-100 group-focus-visible/status:translate-y-0 group-focus-visible/status:opacity-100',
};

export const escapeHtml = (value) => value.toString().replace(/[&<>"']/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
}[character]));

const getSubtotal = (order) => {
    const totalAmount = Number.parseFloat(order.totalAmount);

    if (Number.isFinite(totalAmount)) {
        return totalAmount;
    }

    return order.products.reduce((total, product) => total + (product.quantity * product.price), 0);
};
const getQuantity = (order) => order.products.reduce((total, product) => total + product.quantity, 0);
const getStatus = (status) => orderStatuses[status] || orderStatuses.pending;

const tooltip = (label, groupName) => `
    <span class="pointer-events-none absolute bottom-full left-1/2 z-20 mb-2 min-w-max -translate-x-1/2 translate-y-1 rounded-lg bg-[#3b1728] px-2.5 py-1 text-xs font-extrabold text-white opacity-0 shadow-lg transition ${tooltipInteractionClasses[groupName]}">
        ${escapeHtml(label)}
    </span>
`;

export const renderStatusBadge = (status) => {
    const statusMeta = getStatus(status);

    return `
        <span class="group/status relative inline-flex h-10 w-10 items-center justify-center rounded-full ring-1 transition hover:-translate-y-0.5 ${statusMeta.badgeClass}" aria-label="${escapeHtml(statusMeta.label)}" title="${escapeHtml(statusMeta.label)}">
            ${statusIcons[status] || statusIcons.pending}
            ${tooltip(statusMeta.label, 'status')}
        </span>
    `;
};

const renderActionButton = (action, orderId, label, icon, style) => `
    <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-love-pink-100 ${style}" type="button" aria-label="${escapeHtml(label)}" title="${escapeHtml(label)}" data-order-action="${action}" data-order-id="${escapeHtml(orderId)}">
        ${icon}
        ${tooltip(label, 'action')}
    </button>
`;

const getOrderActions = (order) => {
    if (order.status === 'pending') {
        return [
            renderActionButton('confirm', order.id, 'Approve order', orderIcons.check, actionStyles.confirm),
            renderActionButton('cancel', order.id, 'Cancel order', orderIcons.x, actionStyles.cancel),
            renderActionButton('view', order.id, 'View order details', orderIcons.eye, actionStyles.view),
        ].join('');
    }

    if (order.status === 'preparing') {
        return [
            renderActionButton('ship', order.id, 'Mark for delivery', orderIcons.truck, actionStyles.ship),
            renderActionButton('view', order.id, 'View order details', orderIcons.eye, actionStyles.view),
        ].join('');
    }

    if (order.status === 'out_for_delivery') {
        return renderActionButton('view', order.id, 'View order details', orderIcons.eye, actionStyles.view);
    }

    return renderActionButton('view', order.id, 'View order details', orderIcons.eye, actionStyles.view);
};

const renderProductsCell = (order) => {
    const firstProduct = order.products[0];
    const remainingProducts = order.products.length - 1;

    return `
        <div class="flex min-w-0 items-center gap-3">
            <img class="h-12 w-12 shrink-0 rounded-xl object-cover ring-1 ring-love-pink-100" src="${escapeHtml(firstProduct.image)}" alt="${escapeHtml(firstProduct.name)} thumbnail" loading="lazy">
            <div class="min-w-0">
                <p class="truncate font-extrabold text-[#512438]">${escapeHtml(firstProduct.name)}</p>
                <p class="mt-1 text-xs font-bold text-[#9a6c7b]">${remainingProducts > 0 ? `+${remainingProducts} more` : 'Single dessert'}</p>
            </div>
        </div>
    `;
};

export const renderOrderRow = (order) => `
    <tr class="group/row" data-order-row data-order-status="${escapeHtml(order.status)}">
        <td class="rounded-l-[1.25rem] border-y border-l border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#3b1728] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${escapeHtml(order.id)}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-bold text-[#512438] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${escapeHtml(order.customerName)}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${renderProductsCell(order)}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#512438] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${getQuantity(order)}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#3b1728] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${moneyFormatter.format(getSubtotal(order))}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-medium text-[#9a6c7b] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${escapeHtml(order.dateOrdered)}</td>
        <td class="border-y border-love-pink-100 bg-white px-4 py-4 text-center shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">${renderStatusBadge(order.status)}</td>
        <td class="rounded-r-[1.25rem] border-y border-r border-love-pink-100 bg-white px-4 py-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">
            <div class="flex min-w-36 flex-wrap gap-2">${getOrderActions(order)}</div>
        </td>
    </tr>
`;

const renderDetailField = (label, value) => `
    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4">
        <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">${escapeHtml(label)}</p>
        <p class="mt-2 text-sm font-extrabold text-[#512438]">${escapeHtml(value)}</p>
    </div>
`;

export const renderOrderDetails = (order) => {
    const hiddenProductCount = Math.max(0, order.products.length - 2);
    const products = order.products.map((product, index) => `
        <li class="${index > 1 ? 'hidden' : 'flex'} items-center gap-3 rounded-[1.25rem] border border-love-pink-100 bg-white p-3" ${index > 1 ? 'data-details-extra-product' : ''}>
            <img class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-love-pink-100" src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" loading="lazy">
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-extrabold text-[#512438]">${escapeHtml(product.name)}</p>
                <p class="mt-1 text-xs font-bold text-[#9a6c7b]">Quantity ${product.quantity}</p>
            </div>
            <p class="text-sm font-extrabold text-[#3b1728]">${moneyFormatter.format(product.price)}</p>
        </li>
    `).join('');

    return `
        <div class="grid gap-5">
            <div class="grid gap-3 sm:grid-cols-2">
                ${renderDetailField('Order ID', order.id)}
                ${renderDetailField('Customer name', order.customerName)}
                ${renderDetailField('Contact number', order.contactNumber)}
                ${renderDetailField('Date ordered', order.dateOrdered)}
            </div>
            <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4">
                <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Delivery address</p>
                <p class="mt-2 text-sm font-extrabold text-[#512438]">${escapeHtml(order.deliveryAddress)}</p>
            </div>
            <section>
                <div class="mb-3 flex items-center justify-between gap-4">
                    <h3 class="text-base font-extrabold text-[#3b1728]">Ordered products</h3>
                    ${renderStatusBadge(order.status)}
                </div>
                <ul class="grid gap-3">${products}</ul>
                ${hiddenProductCount > 0 ? `
                    <button class="mt-3 inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-details-show-more>
                        Show ${hiddenProductCount} more
                    </button>
                ` : ''}
            </section>
            <div class="grid gap-3 rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4">
                <div class="flex items-center justify-between gap-4 text-sm font-bold text-[#9a6c7b]">
                    <span>Subtotal</span>
                    <span>${moneyFormatter.format(getSubtotal(order))}</span>
                </div>
                <div class="flex items-center justify-between gap-4 border-t border-love-pink-100 pt-3 text-lg font-extrabold text-[#3b1728]">
                    <span>Total amount</span>
                    <span>${moneyFormatter.format(getSubtotal(order))}</span>
                </div>
            </div>
            ${order.status === 'cancelled' ? `
                <div class="rounded-[1.25rem] border border-rose-100 bg-rose-50 p-4">
                    <p class="text-xs font-extrabold uppercase tracking-wide text-rose-500">Cancellation reason</p>
                    <p class="mt-2 text-sm font-extrabold text-[#512438]">${escapeHtml(order.cancellationReason || 'No reason recorded.')}</p>
                </div>
            ` : ''}
        </div>
    `;
};

export const renderToast = (message) => `
    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">${orderIcons.check}</span>
    <span class="min-w-0">${escapeHtml(message)}</span>
`;
