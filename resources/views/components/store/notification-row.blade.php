@props([
    'title',
    'message',
    'time',
    'icon' => 'bag',
    'tone' => 'pink',
    'unread' => false,
])

@php
    $tones = [
        'pink' => 'bg-love-pink-400 text-white',
        'blue' => 'bg-love-blue-300 text-[#17324d]',
        'green' => 'bg-[#4ade80] text-white',
        'orange' => 'bg-love-orange-400 text-[#512438]',
        'purple' => 'bg-[#c084fc] text-white',
    ];

    $toneClass = $tones[$tone] ?? $tones['pink'];
@endphp

<article class="grid gap-4 rounded-[1.1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)] sm:grid-cols-[3.5rem_minmax(0,1fr)_auto] sm:items-center {{ $unread ? 'border-l-4 border-love-pink-400' : 'border border-love-pink-100/70' }}" data-customer-notification-row>
    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] {{ $toneClass }}">
        @switch($icon)
            @case('payment')
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h14.5v9.5H4.75zM4.75 10.75h14.5" />
                </svg>
                @break

            @case('promo')
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 5.75v6.5l7 7 7-7-7-7h-7Z" />
                    <path stroke-linecap="round" d="M8.25 8.25h.01" />
                </svg>
                @break

            @case('delivery')
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h9.5v8.5h-9.5zM15.25 10.25h2.5l2.5 3v3h-5M8.5 19.25h.01M17.5 19.25h.01" />
                </svg>
                @break

            @case('prep')
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 8.75h12.5M7.75 8.75l1 10.5h6.5l1-10.5M9.25 8.75a2.75 2.75 0 0 1 5.5 0" />
                    <path stroke-linecap="round" d="M9.75 13.25h4.5" />
                </svg>
                @break

            @default
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" />
                </svg>
        @endswitch
    </span>

    <span class="min-w-0">
        <strong class="block text-base text-[#3b1728]">{{ $title }}</strong>
        <span class="mt-1 block text-sm font-medium leading-6 text-[#9a6c7b]">{{ $message }}</span>
    </span>

    <span class="text-left text-xs font-semibold text-[#9a6c7b] sm:text-right">{{ $time }}</span>
</article>
