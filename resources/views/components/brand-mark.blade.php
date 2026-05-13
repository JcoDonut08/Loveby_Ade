@props([
    'href' => route('home'),
    'theme' => 'dark',
])

@php
    $textClass = $theme === 'light' ? 'text-white' : 'text-slate-900';
@endphp

<a class="inline-flex items-center gap-3 {{ $textClass }}" href="{{ $href }}">
    <span class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border border-white bg-white p-px shadow-[0_10px_24px_-18px_rgba(56,189,248,0.5)] ring-1 ring-white/90">
        <img class="h-full w-full rounded-full object-cover" src="{{ asset('images/lovebyadelogo.png') }}" alt="Loveby_Ade logo">
    </span>
    <span class="block">
        <span class="block font-display text-2xl leading-none">Loveby_Ade</span>
    </span>
</a>
