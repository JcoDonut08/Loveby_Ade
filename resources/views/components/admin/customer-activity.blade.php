@props([
    'activities' => [],
])

<section id="customer-activity" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div>
        <h2 class="text-2xl font-extrabold text-[#3b1728]">Recent activity</h2>
        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Customer pulse</p>
    </div>

    <div class="mt-7 grid gap-6">
        @forelse ($activities as $activity)
            <article class="grid grid-cols-[3.5rem_1fr] items-start gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-full {{ $activity['tone'] }}">
                    @if ($activity['type'] === 'order')
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5.75h2l1.5 9h8.25l1.5-6.5H8" />
                            <path stroke-linecap="round" d="M10 19.25h.01M16 19.25h.01" />
                        </svg>
                    @else
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v4.5M12 14.75v4.5M4.75 12h4.5M14.75 12h4.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.75 7.75 10 10M14 14l2.25 2.25M16.25 7.75 14 10M10 14l-2.25 2.25" />
                        </svg>
                    @endif
                </span>
                <div class="min-w-0">
                    <p class="text-base text-[#9a6c7b]"><span class="font-extrabold text-[#3b1728]">{{ $activity['name'] }}</span> {{ $activity['message'] }}</p>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">{{ $activity['detail'] }}</p>
                </div>
            </article>
        @empty
            <article class="grid grid-cols-[3.5rem_1fr] items-start gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-love-cream text-[#512438]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 18.25 6 14.75a7 7 0 1 1 3.25 3.25l-4.5.25Z" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-base text-[#9a6c7b]"><span class="font-extrabold text-[#3b1728]">No customer activity yet</span></p>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">New orders and accounts will show here</p>
                </div>
            </article>
        @endforelse
    </div>
</section>
