@props([
    'title',
    'description',
    'icon' => 'sparkle',
    'actionLabel' => null,
    'actionHref' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-[1.5rem] border border-dashed border-love-pink-200 bg-white/86 p-8 text-center shadow-[0_22px_55px_-44px_rgba(15,23,42,0.35)]']) }}>
    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-love-pink-100 text-love-pink-500">
        @switch($icon)
            @case('bell')
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                </svg>
                @break

            @case('heart')
                <svg class="h-8 w-8 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m12 20.25-1.1-1C5.4 14.26 2.25 11.39 2.25 7.88A4.88 4.88 0 0 1 7.12 3c1.86 0 3.65.86 4.88 2.21A6.57 6.57 0 0 1 16.88 3a4.88 4.88 0 0 1 4.87 4.88c0 3.51-3.15 6.38-8.65 11.37l-1.1 1Z" />
                </svg>
                @break

            @case('cart')
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="9" cy="19" r="1.5" />
                    <circle cx="17" cy="19" r="1.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h2l2.1 10.25a1 1 0 0 0 1 .8h8.92a1 1 0 0 0 1-.78L20 7.5H6.25" />
                </svg>
                @break

            @default
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v5M12 14.25v5M4.75 12h5M14.25 12h5" />
                </svg>
        @endswitch
    </span>

    <h2 class="mt-5 font-display text-3xl text-slate-950">{{ $title }}</h2>
    <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-slate-500">{{ $description }}</p>

    @if ($actionLabel && $actionHref)
        <a class="mt-6 inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ $actionHref }}">
            {{ $actionLabel }}
        </a>
    @endif
</section>
