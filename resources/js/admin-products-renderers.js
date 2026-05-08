import { fallbackProductImage } from './admin-products-data';

const moneyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    maximumFractionDigits: 2,
});

const productIcon = (paths) => `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">${paths}</svg>`;

const productIcons = {
    edit: productIcon('<path stroke-linecap="round" stroke-linejoin="round" d="m4.75 17.75-.25 1.75 1.75-.25 10.9-10.9-1.5-1.5-10.9 10.9Z" /><path stroke-linecap="round" stroke-linejoin="round" d="m14.75 7.75 1.5-1.5a1.5 1.5 0 0 1 2.12 0l.38.38a1.5 1.5 0 0 1 0 2.12l-1.5 1.5" />'),
    trash: productIcon('<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />'),
    check: productIcon('<path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />'),
};

export const escapeHtml = (value) => (value ?? '').toString().replace(/[&<>"']/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
}[character]));

const getStatus = (stock) => {
    if (stock <= 0) {
        return {
            label: 'Out of stock',
            className: 'border-rose-100 bg-rose-50 text-rose-500',
        };
    }

    if (stock <= 10) {
        return {
            label: 'Low stock',
            className: 'border-amber-100 bg-amber-50 text-[#7a4b21]',
        };
    }

    return {
        label: 'In stock',
        className: 'border-emerald-100 bg-emerald-50 text-emerald-600',
    };
};

const tooltip = (label) => `
    <span class="pointer-events-none absolute bottom-full left-1/2 z-20 mb-2 min-w-max -translate-x-1/2 translate-y-1 rounded-lg bg-[#3b1728] px-2.5 py-1 text-xs font-extrabold text-white opacity-0 shadow-lg transition group-hover/action:translate-y-0 group-hover/action:opacity-100 group-focus-visible/action:translate-y-0 group-focus-visible/action:opacity-100">
        ${escapeHtml(label)}
    </span>
`;

const renderActionButton = (action, product, label, icon, className) => `
    <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-love-pink-100 ${className}" type="button" aria-label="${escapeHtml(label)}" title="${escapeHtml(label)}" data-product-action="${action}" data-product-id="${escapeHtml(product.id)}">
        ${icon}
        ${tooltip(label)}
    </button>
`;

const getPrimaryImage = (product) => product.images?.[0] || fallbackProductImage;

export const renderProductCard = (product) => {
    const status = getStatus(Number.parseInt(product.stock, 10));
    const primaryImage = getPrimaryImage(product);
    const extraImageCount = Math.max(0, (product.images?.length || 0) - 1);

    return `
        <article class="overflow-hidden rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_70px_-46px_rgba(244,114,168,0.45)]" data-product-card="${escapeHtml(product.id)}">
            <div class="relative aspect-[4/3] overflow-hidden bg-[#fff4f7]">
                <img class="h-full w-full object-cover transition duration-500 hover:scale-[1.04]" src="${escapeHtml(primaryImage.src)}" alt="${escapeHtml(primaryImage.alt || product.name)}" loading="lazy">
                <span class="absolute right-3 top-3 inline-flex items-center justify-center rounded-full border px-3 py-1 text-xs font-extrabold uppercase tracking-wide ${status.className}">
                    ${status.label}
                </span>
                ${extraImageCount > 0 ? `
                    <span class="absolute bottom-3 left-3 inline-flex items-center justify-center rounded-full bg-white/92 px-3 py-1 text-xs font-extrabold text-[#512438] shadow-sm backdrop-blur-sm">
                        +${extraImageCount} images
                    </span>
                ` : ''}
            </div>

            <div class="grid min-h-60 gap-3 p-5">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wide text-love-pink-500">${escapeHtml(product.category)}</p>
                    <h2 class="mt-2 line-clamp-2 min-h-[3rem] text-lg font-extrabold leading-6 text-[#3b1728]">${escapeHtml(product.name)}</h2>
                    <p class="mt-2 line-clamp-2 min-h-10 text-sm font-medium leading-5 text-[#9a6c7b]">${escapeHtml(product.description)}</p>
                </div>

                <div class="mt-auto flex items-end justify-between gap-4">
                    <div>
                        <p class="text-2xl font-extrabold tracking-tight text-love-pink-400">${moneyFormatter.format(product.price)}</p>
                        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">${Number.parseInt(product.stock, 10)} in stock</p>
                    </div>

                    <div class="flex items-center gap-2">
                        ${renderActionButton('edit', product, 'Edit product', productIcons.edit, 'border-love-pink-100 bg-love-cream text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500')}
                        ${renderActionButton('delete', product, 'Delete product', productIcons.trash, 'border-rose-100 bg-rose-50 text-rose-500 hover:bg-rose-100')}
                    </div>
                </div>
            </div>
        </article>
    `;
};

export const renderProductToast = (message) => `
    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">${productIcons.check}</span>
    <span class="min-w-0">${escapeHtml(message)}</span>
`;
