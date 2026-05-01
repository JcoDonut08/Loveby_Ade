@props([
    'name',
    'date',
    'variation',
    'rating' => 5,
    'quote',
    'likes' => '0',
    'avatar',
    'mediaOne' => null,
    'mediaTwo' => null,
    'mediaThree' => null,
])

<article {{ $attributes->merge(['class' => 'border-b border-slate-100 py-6 last:border-b-0']) }}>
    <div class="grid gap-4 sm:grid-cols-[3rem_1fr]">
        <img class="h-12 w-12 rounded-full object-cover ring-2 ring-love-pink-100" src="{{ $avatar }}" alt="{{ $name }} profile photo" loading="lazy">

        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-950">{{ $name }}</p>

            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <x-product.rating-stars :rating="$rating" size="h-4 w-4" />
                <span>{{ $date }}</span>
                <span>|</span>
                <span>{{ $variation }}</span>
            </div>

            <p class="mt-4 max-w-5xl text-sm leading-7 text-slate-700 sm:text-base">{{ $quote }}</p>

            @if ($mediaOne || $mediaTwo || $mediaThree)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ([$mediaOne, $mediaTwo, $mediaThree] as $media)
                        @if ($media)
                            <img class="h-20 w-20 rounded-lg object-cover" src="{{ $media }}" alt="Review media" loading="lazy">
                        @endif
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex items-center gap-2 text-xs font-medium text-slate-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21H4.75A1.75 1.75 0 0 1 3 19.25v-7.5A1.75 1.75 0 0 1 4.75 10H7.5v11Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10l3.25-6.25A2 2 0 0 1 14.5 4.7V9h4.25a2 2 0 0 1 1.96 2.4l-1.2 6A4.5 4.5 0 0 1 15.1 21H7.5" />
                </svg>
                <span>{{ $likes }}</span>
            </div>
        </div>
    </div>
</article>
