@php
    $filters = [
        ['key' => 'all', 'label' => 'All'],
        ['key' => 'shake/coffee', 'label' => 'Shake/Coffee'],
        ['key' => 'pastries', 'label' => 'Pastries'],
        ['key' => 'cookies', 'label' => 'Cookies'],
        ['key' => 'cakes', 'label' => 'Cakes'],
    ];

    $categories = [
        'Shake/Coffee',
        'Pastries',
        'Cookies',
        'Cakes',
    ];
@endphp

<section class="grid gap-5" data-admin-products>
    <div class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-4 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] sm:p-5">
        <div class="flex flex-col gap-4 2xl:flex-row 2xl:items-center 2xl:justify-between">
            <label class="relative w-full max-w-2xl" for="admin-product-search">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="11" cy="11" r="6.5" />
                        <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                    </svg>
                </span>
                <input class="h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-product-search" type="search" placeholder="Search desserts..." data-product-search>
            </label>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between 2xl:justify-end">
                <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Product category filters">
                    @foreach ($filters as $filter)
                        <button class="inline-flex h-10 shrink-0 items-center justify-center rounded-full px-4 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $loop->first ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]' : 'border border-love-pink-100 bg-love-cream text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" type="button" role="tab" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" data-product-filter="{{ $filter['key'] }}">
                            {{ $filter['label'] }}
                        </button>
                    @endforeach
                </div>

                <button class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-product-open-add>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                    </svg>
                    <span>Add product</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4" data-product-grid></div>

    <div class="hidden rounded-[1.25rem] border border-dashed border-love-pink-200 bg-white/92 p-8 text-center shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-product-empty>
        <p class="text-base font-extrabold text-[#512438]">No desserts match this view.</p>
        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Try another category or search term.</p>
    </div>

    <nav class="flex flex-col gap-4 rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 px-5 py-4 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] xl:flex-row xl:items-center xl:justify-between" aria-label="Product grid pagination">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5">
            <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-products-page-size">
                <span>Products per page</span>
                <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-products-page-size" data-product-page-size>
                    <option value="4">4 products</option>
                    <option value="8" selected>8 products</option>
                    <option value="12">12 products</option>
                </select>
            </label>

            <p class="text-sm font-medium text-[#9a6c7b]" data-product-pagination-status>Showing 0-0 of 0 products</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-product-page-previous disabled>
                Previous
            </button>
            <div class="flex flex-wrap items-center gap-2" data-product-page-buttons></div>
            <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-product-page-next disabled>
                Next
            </button>
        </div>
    </nav>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-product-modal aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close product form" data-product-modal-close></button>

        <section class="relative max-h-[calc(100vh-3rem)] w-full max-w-2xl overflow-y-auto rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Product Details</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-[#3b1728]" data-product-modal-title>Add product</h2>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close product form" data-product-modal-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <form class="mt-6 grid gap-5" data-product-form>
                <input type="hidden" data-product-id>

                <label class="block" for="catalog-product-images">
                    <span class="text-sm font-extrabold text-[#512438]">Upload images</span>
                    <span class="mt-2 flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream px-4 py-6 text-center transition hover:border-love-pink-300 hover:bg-white">
                        <svg class="h-8 w-8 text-love-pink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.25V4.75M8.25 8.25 12 4.5l3.75 3.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 14.75v3.5h12.5v-3.5" />
                        </svg>
                        <span class="mt-3 text-sm font-extrabold text-[#512438]">Choose one or more product images</span>
                        <span class="mt-1 text-xs font-medium text-[#9a6c7b]">JPG, PNG, or WebP. This preview is mock UI only for now.</span>
                    </span>
                    <input class="sr-only" id="catalog-product-images" type="file" accept="image/*" multiple data-product-images>
                </label>

                <div class="grid gap-3 sm:grid-cols-4" data-product-image-preview></div>

                <label class="block" for="catalog-product-name">
                    <span class="text-sm font-extrabold text-[#512438]">Product name</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-name" type="text" placeholder="Dessert name" data-product-name required>
                </label>

                <label class="block" for="catalog-product-description">
                    <span class="text-sm font-extrabold text-[#512438]">Description</span>
                    <textarea class="mt-2 min-h-28 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-description" placeholder="Short product description" data-product-description required></textarea>
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block" for="catalog-product-category">
                        <span class="text-sm font-extrabold text-[#512438]">Category</span>
                        <select class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-category" data-product-category required>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block" for="catalog-product-price">
                        <span class="text-sm font-extrabold text-[#512438]">Price</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-price" type="number" min="0" step="0.01" data-product-price required>
                    </label>

                    <label class="block" for="catalog-product-stock">
                        <span class="text-sm font-extrabold text-[#512438]">Stock quantity</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-stock" type="number" min="0" step="1" data-product-stock required>
                    </label>
                </div>

                <p class="hidden text-sm font-bold text-rose-500" data-product-validation>Please complete the product details.</p>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-product-modal-close>
                        Cancel
                    </button>
                    <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" data-product-save>
                        Save product
                    </button>
                </div>
            </form>
        </section>
    </div>

    <div class="fixed right-4 top-4 z-[60] grid w-[calc(100%-2rem)] max-w-sm gap-3 sm:right-6 sm:top-6" data-product-toast-region aria-live="polite" aria-relevant="additions"></div>
</section>
