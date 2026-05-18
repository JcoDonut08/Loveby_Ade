@props([
    'periods' => [],
])

@php
    $periodLabels = [
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];
    $defaultPeriod = 'weekly';
    $gridClasses = [
        4 => 'grid-cols-4',
        5 => 'grid-cols-5',
        6 => 'grid-cols-6',
        7 => 'grid-cols-7',
    ];
@endphp

<section id="sales-performance" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-sales-performance data-sales-default="{{ $defaultPeriod }}">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">Sales performance</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]" data-sales-caption>{{ $periods[$defaultPeriod]['caption'] ?? 'Track your bakery\'s sweet revenue' }}</p>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#3b1728]" data-sales-total-value>{{ $periods[$defaultPeriod]['total'] ?? 'â‚±0' }}</p>
        </div>

        <div class="grid grid-cols-4 gap-1 rounded-full bg-[#f7f0ea] p-1">
            @foreach ($periodLabels as $period => $label)
                @php
                    $isActive = $period === $defaultPeriod;
                    $periodData = $periods[$period] ?? ['total' => '₱0', 'caption' => 'No sales recorded yet'];
                @endphp

                <button class="{{ $isActive ? 'rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[#512438] shadow-sm' : 'rounded-full px-4 py-2 text-sm font-extrabold text-[#9a6c7b] transition hover:text-love-pink-500' }}" type="button" data-sales-filter="{{ $period }}" data-sales-period-total="{{ $periodData['total'] }}" data-sales-caption="{{ $periodData['caption'] }}" aria-pressed="{{ $isActive ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <div class="mt-8 overflow-x-auto">
        <div class="min-w-[42rem]">
            <div class="relative h-80 pl-16">
                <div class="absolute inset-y-0 left-0 grid w-12 grid-rows-5 text-right text-sm font-medium text-[#9a6c7b]">
                    @foreach (($periods[$defaultPeriod]['axis'] ?? ['0', '0', '0', '0', '0']) as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>

                <div class="absolute inset-y-2 left-16 right-0 grid grid-rows-5">
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                </div>

                @foreach ($periodLabels as $period => $label)
                    @php
                        $bars = collect($periods[$period]['bars'] ?? []);
                        $gridClass = $gridClasses[$bars->count()] ?? 'grid-cols-7';
                    @endphp

                    <div class="absolute bottom-0 left-16 right-0 grid h-[18.75rem] {{ $gridClass }} items-end gap-6" data-sales-bars="{{ $period }}" @if ($period !== $defaultPeriod) hidden @endif>
                        @forelse ($bars as $bar)
                            <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]" title="{{ $bar['amount'] ?? '₱0' }}">
                                <span class="rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]" style="height: {{ (int) ($bar['height'] ?? 0) }}px"></span>
                                {{ $bar['label'] }}
                            </div>
                        @empty
                            <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-0 rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>No sales</div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
