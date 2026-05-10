@props([
    'image',
    'title',
    'price',
    'sold',
    'stockLeft',
    'rating',
    'href' => null,
    'slug' => null,
])

@php
    $filledStars = max(0, min(5, (int) round((float) $rating)));
    $productHref = $href ?? route('products.show');
    $productSlug = $slug ?? \Illuminate\Support\Str::afterLast($productHref, '/');
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-[0_22px_50px_-34px_rgba(15,23,42,0.34)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_30px_68px_-36px_rgba(244,114,168,0.3)]">
    <div class="relative overflow-hidden bg-slate-100">
        <a class="block" href="{{ $productHref }}" aria-label="View {{ $title }} details">
            <img class="aspect-[4/3] w-full object-cover object-center transition duration-500 group-hover:scale-[1.04]" src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        </a>

        <button class="absolute right-3 top-3 inline-flex h-10 w-10 items-center justify-center rounded-full border border-transparent bg-white/92 text-slate-500 shadow-[0_16px_30px_-20px_rgba(15,23,42,0.35)] backdrop-blur-sm transition hover:-translate-y-0.5 hover:text-love-pink-500" type="button" aria-label="Add {{ $title }} to favorites" aria-pressed="false" data-favorite-toggle>
            <svg class="h-5 w-5 fill-transparent" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" data-favorite-icon>
                <path stroke-linecap="round" stroke-linejoin="round" d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
            </svg>
        </button>
    </div>

    <div class="grid flex-1 grid-rows-[auto_auto_auto_auto_auto] p-4 sm:p-5">
        <div class="flex min-h-6 flex-wrap gap-1.5">
            <span class="inline-flex items-center rounded-full border border-love-pink-200 bg-love-pink-100/80 px-2.5 py-1 text-[11px] font-semibold leading-none text-love-pink-500">
                Best seller
            </span>
            <span class="inline-flex items-center rounded-full border border-love-blue-200 bg-love-blue-100/90 px-2.5 py-1 text-[11px] font-semibold leading-none text-love-blue-500">
                Fresh baked
            </span>
        </div>

        <h3 class="mt-3 h-[2.9rem] overflow-hidden text-[1.2rem] leading-[1.45rem] font-semibold text-slate-900">
            <a class="transition hover:text-love-pink-500" href="{{ $productHref }}">{{ $title }}</a>
        </h3>

        <div class="mt-4 grid grid-cols-[1fr_auto] items-end gap-3">
            <p class="text-[1.3rem] font-bold leading-none text-slate-950">
                @if (is_numeric($price))
                    &#8369;{{ number_format((float) $price) }}
                @else
                    {{ $price }}
                @endif
            </p>
            <p class="w-20 text-right text-sm font-medium text-slate-500">{{ $sold }}</p>
        </div>

        <div class="mt-5 flex items-center justify-between gap-4 border-t border-slate-100 pt-4">
            <div class="flex items-center gap-1 text-amber-400">
                @for ($star = 1; $star <= 5; $star++)
                    <svg class="h-4 w-4 {{ $star <= $filledStars ? 'fill-current' : 'fill-slate-200 text-slate-200' }}" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                    </svg>
                @endfor
                <span class="ml-1 text-sm font-semibold text-slate-700">{{ number_format((float) $rating, 1) }}</span>
            </div>
            <span class="inline-flex min-w-[6.25rem] items-center justify-center rounded-full bg-love-blue-100 px-3 py-1.5 text-xs font-semibold text-love-blue-500">
                {{ $stockLeft }}
            </span>
        </div>

        <button class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 disabled:cursor-wait disabled:opacity-70" type="button" data-add-to-cart data-product-slug="{{ $productSlug }}">
            <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <circle cx="9" cy="19" r="1.5" />
                <circle cx="17" cy="19" r="1.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h2l2.1 10.25a1 1 0 0 0 1 .8h8.92a1 1 0 0 0 1-.78L20 7.5H6.25" />
            </svg>
            <span data-add-to-cart-label>Add to cart</span>
        </button>
    </div>
</article>
