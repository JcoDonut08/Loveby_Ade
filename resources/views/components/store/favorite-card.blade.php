@props([
    'image',
    'title',
    'price',
    'rating',
])

@php
    $filledStars = max(0, min(5, (int) round((float) $rating)));
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-[1.5rem] border border-love-pink-100/80 bg-white shadow-[0_22px_50px_-34px_rgba(15,23,42,0.34)]" data-favorite-card>
    <div class="relative overflow-hidden bg-slate-100">
        <img class="aspect-[4/3] w-full object-cover object-center transition duration-500 group-hover:scale-[1.04]" src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <button class="absolute right-3 top-3 inline-flex h-10 w-10 items-center justify-center rounded-full border border-love-pink-500 bg-love-pink-500 text-white shadow-[0_16px_30px_-20px_rgba(15,23,42,0.35)] transition hover:-translate-y-0.5" type="button" aria-label="Remove {{ $title }} from favorites" aria-pressed="true" data-favorite-toggle>
            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" data-favorite-icon>
                <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
            </svg>
        </button>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="text-xl font-extrabold text-slate-950">{{ $title }}</h3>
        <div class="mt-3 flex items-center justify-between gap-4">
            <p class="text-2xl font-extrabold text-love-orange-500">&#8369;{{ $price }}</p>
            <div class="flex items-center gap-1 text-amber-400">
                @for ($star = 1; $star <= 5; $star++)
                    <svg class="h-4 w-4 {{ $star <= $filledStars ? 'fill-current' : 'fill-slate-200 text-slate-200' }}" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                    </svg>
                @endfor
                <span class="ml-1 text-sm font-bold text-slate-700">{{ number_format((float) $rating, 1) }}</span>
            </div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-[1fr_auto]">
            <a class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('cart') }}">
                Add to Cart
            </a>
            <button class="inline-flex items-center justify-center rounded-xl border border-love-pink-200 bg-love-pink-100/70 px-5 py-3 text-sm font-extrabold text-love-pink-500 transition hover:bg-love-pink-200" type="button" data-favorite-remove>
                Remove
            </button>
        </div>
    </div>
</article>
