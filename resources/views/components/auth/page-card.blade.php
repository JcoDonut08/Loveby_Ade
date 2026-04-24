@props([
    'title',
    'description',
])

<div class="relative flex w-full flex-col items-center">
    <section class="w-full max-w-lg rounded-[2rem] border border-white/80 bg-white/92 p-6 shadow-[0_40px_100px_-44px_rgba(15,23,42,0.36)] backdrop-blur-xl sm:p-8">
        <div class="text-center">
            <h1 class="font-display text-[2rem] leading-tight text-slate-900 sm:text-[2.3rem]">{{ $title }}</h1>
            <p class="mt-1.5 text-sm leading-6 text-slate-500">{{ $description }}</p>
        </div>

        {{ $slot }}
    </section>

    <a class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-love-pink-500" href="{{ route('home') }}">
        <span aria-hidden="true">&larr;</span>
        <span>Back to homepage</span>
    </a>
</div>
