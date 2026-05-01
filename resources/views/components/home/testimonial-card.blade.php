@props([
    'name',
    'image',
    'imageAlt' => 'Customer profile photo',
    'rating',
    'quote',
])

@php
    $filledStars = max(0, min(5, (int) round((float) $rating)));
@endphp

<article {{ $attributes->merge(['class' => 'flex h-full min-h-56 flex-col rounded-lg bg-white p-6 text-left shadow-[0_20px_44px_-32px_rgba(15,23,42,0.3)]']) }}>
    <div class="flex min-h-12 items-start gap-3">
        <img class="h-12 w-12 shrink-0 rounded-full object-cover" src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy">

        <div class="min-w-0">
            <h3 class="text-base font-bold leading-5 text-slate-950">{{ $name }}</h3>

            <div class="mt-1.5 flex h-4 items-center gap-0.5 text-amber-400" aria-label="{{ number_format((float) $rating, 1) }} out of 5 stars">
                @for ($star = 1; $star <= 5; $star++)
                    <svg class="h-4 w-4 {{ $star <= $filledStars ? 'fill-current' : 'fill-slate-200 text-slate-200' }}" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                    </svg>
                @endfor
            </div>
        </div>
    </div>

    <p class="mt-5 flex-1 text-sm leading-7 text-slate-700 sm:text-base">
        <em>{{ $quote }}</em>
    </p>
</article>
