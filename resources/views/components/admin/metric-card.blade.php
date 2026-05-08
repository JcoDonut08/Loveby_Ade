@props([
    'title',
    'value',
    'trend',
    'icon' => 'revenue',
    'tone' => 'pink',
    'direction' => 'up',
    'comparison' => 'vs last week',
    'id' => null,
])

@php
    $classes = match ($tone) {
        'purple' => [
            'icon' => 'bg-[#d87adf] text-white',
            'trend' => 'bg-emerald-100 text-emerald-600',
        ],
        'amber' => [
            'icon' => 'bg-[#ffc66d] text-white',
            'trend' => 'bg-emerald-100 text-emerald-600',
        ],
        'blue' => [
            'icon' => 'bg-love-blue-300 text-white',
            'trend' => 'bg-emerald-100 text-emerald-600',
        ],
        'green' => [
            'icon' => 'bg-emerald-300 text-white',
            'trend' => 'bg-red-100 text-red-500',
        ],
        default => [
            'icon' => 'bg-love-pink-400 text-white',
            'trend' => 'bg-emerald-100 text-emerald-600',
        ],
    };
@endphp

<article @if ($id) id="{{ $id }}" @endif class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">{{ $title }}</p>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-[#3b1728]">{{ $value }}</p>
        </div>

        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[1.15rem] {{ $classes['icon'] }}">
            @switch($icon)
                @case('orders')
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" />
                    </svg>
                    @break

                @case('pending')
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v6l3.5 2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                    </svg>
                    @break

                @case('customers')
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.25 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM4.25 19.25a5 5 0 0 1 10 0" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7.25a2.5 2.5 0 0 1 0 5M17 14.25a4.25 4.25 0 0 1 3 4" />
                    </svg>
                    @break

                @case('average')
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 16.25 10 12l3 3 5.25-6.25" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.25 8.75h3v3" />
                    </svg>
                    @break

                @default
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" d="M12 5v14M16.25 8.25H10.5a2.25 2.25 0 0 0 0 4.5h3a2.25 2.25 0 0 1 0 4.5H7.75" />
                    </svg>
            @endswitch
        </span>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-sm font-extrabold {{ $classes['trend'] }}">
            @if ($direction === 'down')
                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 5 7 7M11 12H5M11 12V6" />
                </svg>
            @else
                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 11 7-7M11 4H5M11 4v6" />
                </svg>
            @endif
            {{ $trend }}
        </span>
        <span class="text-sm font-medium text-[#9a6c7b]">{{ $comparison }}</span>
    </div>
</article>
