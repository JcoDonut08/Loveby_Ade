<section id="low-stock" class="relative overflow-hidden rounded-[1.25rem] border border-amber-200 bg-[#fff8ed] p-4 shadow-[0_22px_55px_-46px_rgba(251,191,36,0.58)] sm:p-5">
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 items-center gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-love-pink-200 bg-love-pink-100/70 text-[#7a4b21]">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75 21.25 19H2.75L12 4.75Z" />
                    <path stroke-linecap="round" d="M12 10v4" />
                    <path stroke-linecap="round" d="M12 17.25h.01" />
                </svg>
            </span>
            <div class="min-w-0">
                <h2 class="text-lg font-extrabold text-[#3b1728]">3 products need restocking</h2>
                <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Glazed Vanilla Donuts (6pc) - Sprinkle Party Donuts - Funfetti Birthday Cake</p>
            </div>
        </div>

        <a class="inline-flex h-11 items-center justify-center rounded-full border border-amber-200 bg-white/80 px-5 text-sm font-extrabold text-[#512438] transition hover:-translate-y-0.5 hover:border-love-pink-300 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-amber-100" href="{{ route('admin.dashboard') }}#top-desserts">
            Restock now
        </a>
    </div>

    <span class="absolute -right-5 -top-5 h-24 w-24 rounded-full border-[8px] border-[#512438]/10"></span>
</section>
