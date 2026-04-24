@props([
    'image',
    'eyebrow',
    'title',
    'description',
    'highlight',
])

<aside class="relative hidden h-full overflow-hidden bg-slate-900 lg:block">
    <img class="absolute inset-0 h-full w-full object-cover" src="{{ $image }}" alt="{{ $title }}">
    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.08)_0%,rgba(15,23,42,0.2)_36%,rgba(15,23,42,0.74)_100%)]"></div>
    <div class="absolute inset-x-0 top-0 h-48 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.34),transparent_58%)]"></div>

    <div class="relative flex h-full flex-col justify-between p-8 xl:p-10">
        <x-brand-mark :href="route('login')" theme="light" />

        <div class="pointer-events-none absolute right-8 top-8">
            <article class="w-44 rounded-[1.5rem] border border-white/20 bg-white/18 p-4 text-white shadow-[0_20px_45px_-30px_rgba(15,23,42,0.85)] backdrop-blur-md">
                <p class="text-2xl font-semibold">4.9</p>
                <p class="mt-1 text-[11px] uppercase tracking-[0.28em] text-white/70">Dessert rating</p>
            </article>
        </div>

        <div class="relative max-w-lg">
            <span class="inline-flex rounded-full border border-white/20 bg-white/14 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-pink-200 backdrop-blur-md">
                Members Login
            </span>
            <h2 class="mt-5 max-w-md font-display text-4xl leading-tight text-white xl:text-5xl">{{ $title }}</h2>
            <p class="mt-4 max-w-md text-sm leading-7 text-white/78 xl:text-base">{{ $description }}</p>

            <article class="mt-6 max-w-sm rounded-[1.75rem] border border-white/20 bg-white/16 p-5 text-white shadow-[0_20px_45px_-30px_rgba(15,23,42,0.85)] backdrop-blur-md">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-love-blue-200">{{ $eyebrow }}</p>
                <p class="mt-3 text-sm leading-6 text-white/85">{{ $highlight }}</p>
            </article>
        </div>
    </div>
</aside>
