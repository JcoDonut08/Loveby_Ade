@props([
    'image',
    'title',
    'price',
    'quantity' => 1,
    'note' => 'Fresh baked dessert',
    'slug' => '',
    'stock' => 20,
])

@php
    $lineTotal = (float) $price * (int) $quantity;
@endphp

<article class="grid gap-4 border-b border-love-pink-100/80 py-5 sm:grid-cols-[7.5rem_minmax(0,1fr)]" data-cart-item data-cart-price="{{ $price }}" data-cart-slug="{{ $slug }}">
    <img class="aspect-square w-full rounded-[1.1rem] bg-slate-100 object-cover sm:w-30" src="{{ $image }}" alt="{{ $title }}" loading="lazy">

    <div class="min-w-0">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <h2 class="text-lg font-extrabold text-slate-950">{{ $title }}</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">{{ $note }}</p>
                <p class="mt-2 text-base font-extrabold text-love-orange-500">&#8369;{{ number_format((float) $price, 2) }}</p>
            </div>

            <button class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-love-pink-100 hover:text-love-pink-500" type="button" aria-label="Remove {{ $title }}" data-cart-remove>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="inline-flex w-max items-center gap-2 rounded-full border border-love-pink-100 bg-white p-1">
                <button class="flex h-9 w-9 items-center justify-center rounded-full text-slate-600 transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-40" type="button" aria-label="Decrease {{ $title }} quantity" data-cart-quantity-decrease>-</button>
                <input class="h-9 w-12 rounded-full border-0 bg-transparent text-center text-sm font-extrabold text-slate-950 outline-none" type="number" min="1" max="{{ $stock }}" value="{{ $quantity }}" aria-label="{{ $title }} quantity" data-cart-quantity-input>
                <button class="flex h-9 w-9 items-center justify-center rounded-full text-slate-600 transition hover:bg-love-pink-100 hover:text-love-pink-500" type="button" aria-label="Increase {{ $title }} quantity" data-cart-quantity-increase>+</button>
            </div>

            <p class="text-sm font-bold text-slate-500">Line total <span class="ml-2 text-base text-slate-950" data-cart-line-total>&#8369;{{ number_format($lineTotal, 2) }}</span></p>
        </div>
    </div>
</article>
