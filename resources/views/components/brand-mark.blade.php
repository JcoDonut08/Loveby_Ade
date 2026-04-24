@props([
    'href' => route('home'),
    'theme' => 'dark',
])

@php
    $textClass = $theme === 'light' ? 'text-white' : 'text-slate-900';
@endphp

<a class="inline-flex items-center gap-3 {{ $textClass }}" href="{{ $href }}">
    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#f472a8_0%,#38bdf8_100%)] text-sm font-bold text-white shadow-[0_16px_35px_-20px_rgba(56,189,248,0.6)]">
        LA
    </span>
    <span class="block">
        <span class="block font-display text-2xl leading-none">Loveby_Ade</span>
    </span>
</a>
