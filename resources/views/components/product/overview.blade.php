<section class="mx-auto max-w-[86rem] px-4 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-2xl border border-white/80 bg-white/94 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
        <div class="grid gap-0 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <div class="flex flex-col gap-4 p-4 sm:p-5" data-product-gallery>
                <div class="flex-1 overflow-hidden rounded-xl bg-slate-100">
                    <img
                        class="h-full min-h-[24rem] w-full object-cover sm:min-h-[32rem] lg:min-h-full"
                        src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80"
                        alt="Pastel Donut Box product photo"
                        data-product-main-image
                    >
                </div>

                <div class="grid grid-cols-4 gap-3">
                    <button class="rounded-xl border-2 border-love-pink-400 bg-white p-1" type="button" aria-pressed="true" data-product-thumb data-product-thumb-src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80" data-product-thumb-alt="Pastel Donut Box product photo">
                        <img class="aspect-square w-full rounded-lg object-cover" src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=240&q=80" alt="Pastel Donut Box product thumbnail">
                    </button>
                    <button class="rounded-xl border-2 border-transparent bg-white p-1" type="button" aria-pressed="false" data-product-thumb data-product-thumb-src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=1200&q=80" data-product-thumb-alt="Colorful donut product photo">
                        <img class="aspect-square w-full rounded-lg object-cover" src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=240&q=80" alt="Colorful donut thumbnail">
                    </button>
                    <button class="rounded-xl border-2 border-transparent bg-white p-1" type="button" aria-pressed="false" data-product-thumb data-product-thumb-src="https://images.unsplash.com/photo-1514517604298-cf80e0fb7f1e?auto=format&fit=crop&w=1200&q=80" data-product-thumb-alt="Assorted glazed donuts product photo">
                        <img class="aspect-square w-full rounded-lg object-cover" src="https://images.unsplash.com/photo-1514517604298-cf80e0fb7f1e?auto=format&fit=crop&w=240&q=80" alt="Assorted glazed donuts thumbnail">
                    </button>
                    <button class="rounded-xl border-2 border-transparent bg-white p-1" type="button" aria-pressed="false" data-product-thumb data-product-thumb-src="https://images.unsplash.com/photo-1556913396-7a3c459ef68e?auto=format&fit=crop&w=1200&q=80" data-product-thumb-alt="Dessert box product photo">
                        <img class="aspect-square w-full rounded-lg object-cover" src="https://images.unsplash.com/photo-1556913396-7a3c459ef68e?auto=format&fit=crop&w=240&q=80" alt="Dessert box thumbnail">
                    </button>
                </div>
            </div>

            <article class="border-t border-slate-100 p-6 sm:p-8 lg:border-l lg:border-t-0">
                <a class="inline-flex items-center gap-2 text-sm font-semibold text-love-blue-500 transition hover:text-love-pink-500" href="{{ route('home') }}#products">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11 6-6 6 6 6" />
                    </svg>
                    <span>Back to products</span>
                </a>

            <div class="mt-5 flex flex-wrap gap-2">
                <span class="inline-flex items-center rounded-full border border-love-pink-200 bg-love-pink-100/80 px-2.5 py-1 text-[11px] font-semibold leading-none text-love-pink-500">Best seller</span>
                <span class="inline-flex items-center rounded-full border border-love-blue-200 bg-love-blue-100/90 px-2.5 py-1 text-[11px] font-semibold leading-none text-love-blue-500">Fresh baked</span>
            </div>

            <h1 class="mt-4 font-display text-4xl leading-tight text-slate-950 sm:text-5xl">Pastel Donut Box</h1>
            <p class="mt-3 text-3xl font-bold text-love-orange-500">PHP 120</p>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <x-product.rating-stars rating="4.8" size="h-5 w-5" />
                <span class="font-semibold text-slate-700">4.8</span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span>184 sold</span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span>14 stocks left</span>
            </div>

            <p class="mt-6 text-base leading-8 text-slate-600">
                A soft and colorful box of freshly baked donuts made for gifting, sharing, and sweet cravings. Each box is packed with pastel glaze, playful toppings, and a cozy bakery finish.
            </p>

            <div class="mt-7 border-y border-slate-100 py-5" data-product-quantity>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Quantity</p>
                        <p class="mt-1 text-xs text-slate-500">Maximum of 14 per checkout</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-love-pink-200 hover:text-love-pink-500" type="button" aria-label="Decrease quantity" data-quantity-decrease>
                            <span aria-hidden="true">-</span>
                        </button>
                        <input class="h-10 w-16 rounded-xl border border-slate-200 bg-white text-center text-sm font-semibold text-slate-900 outline-none focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" type="number" min="1" max="14" value="1" aria-label="Product quantity" data-quantity-input>
                        <button class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-love-pink-200 hover:text-love-pink-500" type="button" aria-label="Increase quantity" data-quantity-increase>
                            <span aria-hidden="true">+</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_auto]">
                <button class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="button">
                    Add to cart
                </button>
                <button class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-love-pink-200 hover:text-love-pink-500 sm:w-12" type="button" aria-label="Add to favorites">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
                    </svg>
                </button>
            </div>
            </article>
        </div>
    </div>
</section>
