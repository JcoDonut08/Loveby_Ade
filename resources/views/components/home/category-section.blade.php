<section id="categories" class="py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="inline-flex rounded-full border border-love-blue-200 bg-white/85 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-blue-500">
                    Browse first
                </span>
                <h2 class="mt-4 font-display text-4xl text-slate-900 sm:text-5xl">Shop by category</h2>
            </div>

            <a class="inline-flex items-center gap-2 text-sm font-semibold text-love-blue-500 transition hover:text-love-pink-500" href="{{ route('products.index') }}">
                <span>Browse all categories</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m13 5 7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <x-home.category-card
                title="Cookies"
                image="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=1200&q=80"
                :href="route('products.index', ['category' => 'Cookies'])"
            />
            <x-home.category-card
                title="Pastries"
                image="https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=80"
                :href="route('products.index', ['category' => 'Pastries'])"
            />
            <x-home.category-card
                title="Cakes"
                image="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1200&q=80"
                :href="route('products.index', ['category' => 'Cakes'])"
            />
            <x-home.category-card
                title="Coffees / Shakes"
                image="https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=1200&q=80"
                :href="route('products.index', ['category' => 'Coffees / Shakes'])"
            />
        </div>
    </div>
</section>
