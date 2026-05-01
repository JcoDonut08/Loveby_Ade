@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionHref' => '#',
])

<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div class="max-w-3xl">
        @if ($eyebrow)
            <span class="inline-flex rounded-full border border-love-pink-200 bg-love-pink-100/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-pink-500">
                {{ $eyebrow }}
            </span>
        @endif

        <h2 class="@if($eyebrow) mt-4 @endif font-display text-4xl leading-tight text-slate-900 sm:text-5xl">{{ $title }}</h2>

        @if ($description)
            <p class="mt-4 max-w-xl text-sm leading-7 text-slate-500 sm:text-base">{{ $description }}</p>
        @endif
    </div>

    @if ($actionLabel)
        <a class="inline-flex items-center gap-2 text-sm font-semibold text-love-blue-500 transition hover:text-love-pink-500" href="{{ $actionHref }}">
            <span>{{ $actionLabel }}</span>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                <path stroke-linecap="round" stroke-linejoin="round" d="m13 5 7 7-7 7" />
            </svg>
        </a>
    @endif
</div>
