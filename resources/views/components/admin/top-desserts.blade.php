@props([
    'categories' => [],
])

<section id="top-desserts" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div>
        <h2 class="text-2xl font-extrabold text-[#3b1728]">Top desserts</h2>
        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Best sellers this week</p>
    </div>

    <div class="mt-7 flex justify-center">
        <svg class="h-56 w-56" viewBox="0 0 120 120" role="img" aria-label="Top dessert category donut chart">
            <circle cx="60" cy="60" r="38" fill="none" stroke="#f4edf0" stroke-width="18" />
            @foreach ($categories as $category)
                <circle cx="60" cy="60" r="38" fill="none" stroke="{{ $category['stroke'] }}" stroke-width="18" stroke-dasharray="{{ $category['percent'] }} 100" stroke-dashoffset="{{ $category['offset'] }}" transform="rotate(-90 60 60)" />
            @endforeach
            <circle cx="60" cy="60" r="23" fill="white" />
        </svg>
    </div>

    <div class="mt-6 flex flex-wrap justify-center gap-4">
        @forelse ($categories as $category)
            <span class="inline-flex items-center gap-2 text-sm font-medium {{ $category['class'] }}"><span class="h-4 w-4 rounded-full {{ $category['dot'] }}"></span>{{ $category['label'] }}</span>
        @empty
            <span class="inline-flex items-center gap-2 text-sm font-medium text-[#9a6c7b]"><span class="h-4 w-4 rounded-full bg-love-pink-100"></span>No sales yet</span>
        @endforelse
    </div>
</section>
