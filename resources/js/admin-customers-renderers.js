const moneyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    maximumFractionDigits: 2,
});

const icon = (path) => `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">${path}</svg>`;

const icons = {
    eye: icon('<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12s3-5.25 8.25-5.25S20.25 12 20.25 12s-3 5.25-8.25 5.25S3.75 12 3.75 12Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />'),
};

export const escapeHtml = (value) => (value ?? '').toString().replace(/[&<>"']/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
}[character]));

const getSpentOrders = (customer) => customer.orders.filter((order) => order.countsAsSpent);
export const getCustomerSpend = (customer) => getSpentOrders(customer).reduce((total, order) => total + order.total, 0);
export const getAverageOrderValue = (customer) => {
    const spentOrders = getSpentOrders(customer);

    if (spentOrders.length === 0) {
        return 0;
    }

    return getCustomerSpend(customer) / spentOrders.length;
};

const getSegmentBadge = (customer) => {
    if (customer.segment === 'top_spender') {
        return {
            label: 'Top Spender',
            className: 'border-emerald-100 bg-emerald-50 text-emerald-600',
        };
    }

    if (customer.segment === 'new_customer') {
        return {
            label: 'New Customer',
            className: 'border-love-pink-100 bg-love-pink-100/80 text-love-pink-500',
        };
    }

    return {
        label: 'Regular Customer',
        className: 'border-love-blue-100 bg-love-blue-100/80 text-[#23445c]',
    };
};

export const renderCustomerCard = (customer) => {
    const badge = getSegmentBadge(customer);

    return `
        <article class="grid gap-4 rounded-[1.25rem] border border-love-pink-100 bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition hover:-translate-y-0.5 hover:bg-love-cream sm:grid-cols-[minmax(0,1.4fr)_repeat(3,minmax(8rem,0.5fr))_auto] sm:items-center" data-customer-card="${escapeHtml(customer.id)}">
            <div class="flex min-w-0 items-center gap-4">
                <img class="h-14 w-14 shrink-0 rounded-full object-cover ring-2 ring-love-pink-100" src="${escapeHtml(customer.avatar)}" alt="${escapeHtml(customer.name)} profile image" loading="lazy">
                <div class="min-w-0">
                    <h3 class="truncate text-base font-extrabold text-[#3b1728]">${escapeHtml(customer.name)}</h3>
                    <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">${escapeHtml(customer.email)}</p>
                    <span class="mt-2 inline-flex rounded-full border px-2.5 py-1 text-xs font-extrabold ${badge.className}">${badge.label}</span>
                </div>
            </div>

            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Orders</p>
                <p class="mt-1 text-lg font-extrabold text-[#512438]">${customer.orders.length}</p>
            </div>

            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Spent</p>
                <p class="mt-1 text-lg font-extrabold text-love-pink-400">${moneyFormatter.format(getCustomerSpend(customer))}</p>
            </div>

            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Last Active</p>
                <p class="mt-1 text-sm font-extrabold text-[#512438]">${escapeHtml(customer.lastActive)}</p>
            </div>

            <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-love-pink-100 bg-love-cream text-[#512438] transition hover:-translate-y-0.5 hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View customer purchase history" title="View customer purchase history" data-customer-action="view" data-customer-id="${escapeHtml(customer.id)}">
                ${icons.eye}
            </button>
        </article>
    `;
};

const renderStat = (label, value) => `
    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4">
        <p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">${escapeHtml(label)}</p>
        <p class="mt-2 text-lg font-extrabold text-[#3b1728]">${escapeHtml(value)}</p>
    </div>
`;

export const renderCustomerDetails = (customer) => {
    const purchaseHistory = customer.orders.slice(0, 3).map((order) => `
        <li class="grid gap-3 rounded-[1rem] border border-love-pink-100 bg-white p-4 sm:grid-cols-[minmax(7rem,0.35fr)_minmax(0,1fr)_auto] sm:items-center">
            <div>
                <p class="text-sm font-extrabold text-[#512438]">${escapeHtml(order.id)}</p>
                <p class="mt-1 text-xs font-bold text-[#9a6c7b]">${escapeHtml(order.date)}</p>
            </div>
            <p class="text-sm font-medium leading-6 text-[#512438]">${escapeHtml(order.items)}</p>
            <div class="text-left sm:text-right">
                <p class="text-sm font-extrabold text-love-pink-400">${moneyFormatter.format(order.total)}</p>
                <p class="mt-1 text-xs font-bold text-[#9a6c7b]">${escapeHtml(order.status)}</p>
            </div>
        </li>
    `).join('');

    const activity = customer.activity.map((item) => `
        <li class="flex gap-3 rounded-[1rem] border border-love-pink-100 bg-white p-3">
            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-love-pink-400"></span>
            <span class="text-sm font-medium leading-6 text-[#512438]">${escapeHtml(item)}</span>
        </li>
    `).join('');

    return `
        <div class="grid gap-5">
            <div class="flex flex-col gap-4 rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4 sm:flex-row sm:items-center">
                <img class="h-20 w-20 rounded-full object-cover ring-4 ring-white" src="${escapeHtml(customer.avatar)}" alt="${escapeHtml(customer.name)} profile image">
                <div class="min-w-0 flex-1">
                    <h3 class="text-2xl font-extrabold text-[#3b1728]">${escapeHtml(customer.name)}</h3>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">${escapeHtml(customer.email)} · ${escapeHtml(customer.phone)}</p>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Joined ${escapeHtml(customer.joined)}</p>
                </div>
            </div>

            <section>
                <h3 class="text-base font-extrabold text-[#3b1728]">Spending Summary</h3>
                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                    ${renderStat('Total spent', moneyFormatter.format(getCustomerSpend(customer)))}
                    ${renderStat('Order count', customer.orders.length.toString())}
                    ${renderStat('Average order', moneyFormatter.format(getAverageOrderValue(customer)))}
                </div>
            </section>

            <section>
                <h3 class="text-base font-extrabold text-[#3b1728]">Recent Purchase History</h3>
                <ul class="mt-3 grid gap-3">${purchaseHistory}</ul>
            </section>

            <section>
                <h3 class="text-base font-extrabold text-[#3b1728]">User Activity Tracking</h3>
                <ul class="mt-3 grid gap-2">${activity}</ul>
            </section>
        </div>
    `;
};
