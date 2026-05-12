@php
    $cartCount = app(\App\Services\CartService::class)->count(request());
    $favoriteCount = app(\App\Services\FavoriteService::class)->count(request());
    $authenticatedUser = auth()->user();
    $profilePhotoUrl = $authenticatedUser?->profilePhotoUrl();
@endphp

<header class="fixed inset-x-0 top-0 z-50 border-b border-white/70 bg-white/90 shadow-[0_16px_40px_-32px_rgba(15,23,42,0.45)] backdrop-blur-xl">
    <div class="mx-auto flex max-w-[92rem] items-center gap-6 px-4 py-4 sm:px-6 lg:gap-10 lg:px-10">
        <div class="shrink-0">
            <x-brand-mark :href="route('home')" />
        </div>

        <nav class="hidden items-center gap-8 text-sm font-semibold text-slate-600 lg:flex">
            <a class="transition hover:text-love-pink-500" href="{{ route('home') }}#home">Homepage</a>
            <a class="transition hover:text-love-pink-500" href="{{ route('products.index') }}">Products</a>
            <a class="transition hover:text-love-pink-500" href="{{ route('home') }}#about">About Us</a>
            <a class="transition hover:text-love-pink-500" href="{{ route('contact') }}">Contact</a>
        </nav>

        <form class="relative hidden max-w-xl flex-1 lg:block" action="{{ route('products.index') }}" method="GET" data-search-autocomplete-form data-search-suggestions-url="{{ route('search.suggestions') }}" data-search-recent-destroy-url="{{ route('search.recent.destroy') }}">
            <label class="sr-only" for="site-search">Search products</label>
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                </svg>
            </span>
            <input class="w-full rounded-full border border-slate-200 bg-white/90 px-12 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="site-search" name="search" type="search" value="{{ request('search', '') }}" placeholder="Search cakes, donuts, cookies..." autocomplete="off" data-search-autocomplete-input>
            <div class="absolute left-0 right-0 top-full z-50 mt-2 hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_24px_70px_-36px_rgba(15,23,42,0.42)]" data-search-autocomplete-panel>
                <div class="max-h-96 overflow-y-auto py-2">
                    <div class="hidden" data-search-recent-section>
                        <p class="px-4 pb-1 pt-2 text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Recent searches</p>
                        <div data-search-recent-list></div>
                    </div>

                    <div class="hidden" data-search-suggestion-section>
                        <p class="px-4 pb-1 pt-2 text-xs font-extrabold uppercase tracking-[0.2em] text-love-pink-500">Search recommendations</p>
                        <div data-search-suggestion-list></div>
                    </div>

                    <p class="px-4 py-3 text-sm font-semibold text-slate-500" data-search-empty-state>Start typing to search desserts.</p>
                </div>
            </div>
        </form>

        <div class="ml-auto flex items-center gap-2 sm:gap-3 lg:gap-4">
            <a class="relative flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-600 transition hover:-translate-y-0.5 hover:border-love-pink-200 hover:text-love-pink-500" href="{{ route('favorites') }}" aria-label="Favorites">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
                </svg>
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-pink-400 px-1 text-xs font-extrabold text-white transition duration-300 {{ $favoriteCount === 0 ? 'hidden' : '' }}" data-favorites-nav-count>{{ $favoriteCount }}</span>
            </a>
            <a class="relative flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-600 transition hover:-translate-y-0.5 hover:border-love-pink-200 hover:text-love-pink-500" href="{{ route('notifications') }}" aria-label="Notifications">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                </svg>
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-slate-900">4</span>
            </a>
            <a class="relative flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-600 transition hover:-translate-y-0.5 hover:border-love-pink-200 hover:text-love-pink-500" href="{{ route('cart') }}" aria-label="Shopping cart">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="9" cy="19" r="1.5" />
                    <circle cx="17" cy="19" r="1.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h2l2.1 10.25a1 1 0 0 0 1 .8h8.92a1 1 0 0 0 1-.78L20 7.5H6.25" />
                </svg>
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-pink-400 px-1 text-xs font-extrabold text-white transition duration-300 {{ $cartCount === 0 ? 'hidden' : '' }}" data-cart-nav-count>{{ $cartCount }}</span>
            </a>
            @auth
                <details class="group relative">
                    <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center overflow-hidden rounded-full bg-slate-900 text-sm font-extrabold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100 [&::-webkit-details-marker]:hidden" aria-haspopup="menu">
                        <img class="{{ $profilePhotoUrl ? '' : 'hidden' }} h-full w-full object-cover" src="{{ $profilePhotoUrl ?? '' }}" alt="{{ auth()->user()->name }} profile photo" data-profile-photo-preview-image>
                        <span class="{{ $profilePhotoUrl ? 'hidden' : '' }}" data-profile-photo-preview-fallback>
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </summary>

                    <div class="absolute right-0 top-full z-50 hidden w-64 pt-3 group-open:block">
                        <div class="rounded-2xl border border-white/80 bg-white p-2 shadow-[0_24px_70px_-36px_rgba(15,23,42,0.42)]">
                            <div class="flex items-center gap-3 px-3 py-2">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-love-pink-100 text-sm font-extrabold text-love-pink-500">
                                    <img class="{{ $profilePhotoUrl ? '' : 'hidden' }} h-full w-full object-cover" src="{{ $profilePhotoUrl ?? '' }}" alt="{{ auth()->user()->name }} profile photo" data-profile-photo-preview-image>
                                    <span class="{{ $profilePhotoUrl ? 'hidden' : '' }}" data-profile-photo-preview-fallback>
                                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </span>
                                <span class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs font-medium text-slate-500">{{ auth()->user()->email }}</p>
                                </span>
                            </div>
                            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-love-pink-100 hover:text-love-pink-500" href="{{ route('orders.index') }}">View orders</a>
                            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-love-pink-100 hover:text-love-pink-500" href="{{ route('account') }}">Account settings</a>
                            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-love-pink-100 hover:text-love-pink-500" href="{{ route('delivered-products.index') }}">View delivered products</a>
                            <form class="mt-1 border-t border-slate-100 pt-1" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="block w-full rounded-xl px-3 py-2 text-left text-sm font-semibold text-red-500 transition hover:bg-red-50" type="submit">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            @else
                <a class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('login') }}">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>
<div class="h-[5.35rem]" aria-hidden="true"></div>
