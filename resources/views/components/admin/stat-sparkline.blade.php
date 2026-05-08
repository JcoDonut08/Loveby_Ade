@props([
    'tone' => 'pink',
])

@php
    $strokeClass = match ($tone) {
        'blue' => 'text-love-blue-400',
        'green' => 'text-emerald-400',
        'amber' => 'text-amber-400',
        'rose' => 'text-rose-400',
        default => 'text-love-pink-400',
    };
@endphp

<svg class="h-10 w-24 {{ $strokeClass }}" viewBox="0 0 96 40" fill="none" aria-hidden="true">
    <path d="M2 30c8 0 8-16 16-16s8 12 16 12 8-20 16-20 8 26 16 26 8-14 16-14 8-10 16-10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
    <path class="opacity-20" d="M2 38h92" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
</svg>
