@props([
    'initials',
    'name',
    'action',
    'time',
    'tone' => 'pink',
])

@php
    $avatarClass = match ($tone) {
        'blue' => 'bg-love-blue-100 text-love-blue-500',
        'green' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        default => 'bg-love-pink-100 text-love-pink-500',
    };
@endphp

<article class="flex items-start gap-3 rounded-2xl bg-slate-50 p-3">
    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-extrabold {{ $avatarClass }}">
        {{ $initials }}
    </span>
    <span class="min-w-0 flex-1">
        <span class="block text-sm font-bold text-slate-900">{{ $name }}</span>
        <span class="mt-0.5 block text-sm leading-6 text-slate-500">{{ $action }}</span>
    </span>
    <time class="shrink-0 text-xs font-semibold text-slate-400">{{ $time }}</time>
</article>
