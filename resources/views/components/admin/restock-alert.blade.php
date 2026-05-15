@props([
    'lowStock' => [
        'count' => 0,
        'products' => [],
    ],
])

@php
    $count = (int) ($lowStock['count'] ?? 0);
    $products = collect($lowStock['products'] ?? []);
    $title = $count === 1 ? '1 product needs restocking' : $count.' products need restocking';
    $summary = $products->isNotEmpty() ? $products->join(' - ') : 'All products are stocked right now';
    $isStocked = $count === 0;
    $sectionClasses = $isStocked
        ? 'border-emerald-200 bg-emerald-50 shadow-[0_22px_55px_-46px_rgba(16,185,129,0.46)]'
        : 'border-amber-200 bg-[#fff8ed] shadow-[0_22px_55px_-46px_rgba(251,191,36,0.58)]';
    $iconClasses = $isStocked
        ? 'border-emerald-200 bg-emerald-100 text-emerald-700'
        : 'border-love-pink-200 bg-love-pink-100/70 text-[#7a4b21]';
    $ringClasses = $isStocked ? 'border-emerald-700/10' : 'border-[#512438]/10';
@endphp

<section id="low-stock" class="relative overflow-hidden rounded-[1.25rem] border {{ $sectionClasses }} p-4 sm:p-5">
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 items-center gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border {{ $iconClasses }}">
                @if ($isStocked)
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" role="img" aria-label="Stock levels healthy">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.25 4.25 4.25 8.25-9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                    </svg>
                @else
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" role="img" aria-label="Products need restocking">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75 21.25 19H2.75L12 4.75Z" />
                        <path stroke-linecap="round" d="M12 10v4" />
                        <path stroke-linecap="round" d="M12 17.25h.01" />
                    </svg>
                @endif
            </span>
            <div class="min-w-0">
                <h2 class="text-lg font-extrabold text-[#3b1728]">{{ $title }}</h2>
                <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">{{ $summary }}</p>
            </div>
        </div>

        <a class="inline-flex h-11 items-center justify-center rounded-full border border-amber-200 bg-white/80 px-5 text-sm font-extrabold text-[#512438] transition hover:-translate-y-0.5 hover:border-love-pink-300 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-amber-100" href="{{ route('admin.products') }}">
            Restock now
        </a>
    </div>

    <span class="absolute -right-5 -top-5 h-24 w-24 rounded-full border-[8px] {{ $ringClasses }}"></span>
</section>
