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

        <form class="relative hidden max-w-xl flex-1 lg:block" action="{{ route('products.index') }}" method="GET">
            <label class="sr-only" for="site-search">Search products</label>
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                </svg>
            </span>
            <input class="w-full rounded-full border border-slate-200 bg-white/90 px-12 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="site-search" name="search" type="search" placeholder="Search cakes, donuts, cookies...">
        </form>

        <div class="ml-auto flex items-center gap-2 sm:gap-3 lg:gap-4">
            <a class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-600 transition hover:-translate-y-0.5 hover:border-love-pink-200 hover:text-love-pink-500" href="{{ route('favorites') }}" aria-label="Favorites">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
                </svg>
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
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-pink-400 px-1 text-xs font-extrabold text-white" data-cart-nav-count>4</span>
            </a>
            <a class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('login') }}">
                Login
            </a>
        </div>
    </div>
</header>
<div class="h-[5.35rem]" aria-hidden="true"></div>
