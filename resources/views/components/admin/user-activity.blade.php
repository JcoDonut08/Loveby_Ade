@props([
    'activity' => [
        'axis' => [0, 0, 0, 0, 0],
        'points' => [],
        'path' => '',
    ],
])

<section id="user-activity" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div>
        <h2 class="text-2xl font-extrabold text-[#3b1728]">User activity</h2>
        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Active users this week</p>
    </div>

    <div class="mt-7 overflow-x-auto">
        <svg class="h-80 min-w-[42rem] w-full" viewBox="0 0 760 320" fill="none" role="img" aria-label="Line chart showing active users through the week">
            <path d="M72 44H724M72 104H724M72 164H724M72 224H724M72 284H724" stroke="#f3dce5" stroke-dasharray="5 5" />
            @foreach (($activity['axis'] ?? [0, 0, 0, 0, 0]) as $index => $label)
                <text x="{{ $index === 3 ? 36 : 28 }}" y="{{ [48, 108, 168, 228, 288][$index] }}" fill="#9a6c7b" font-size="14">{{ $label }}</text>
            @endforeach
            <path d="{{ $activity['path'] }}" stroke="#7dd3fc" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
            @foreach (($activity['points'] ?? []) as $point)
                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="7" fill="#7dd3fc" />
            @endforeach
            @foreach (($activity['points'] ?? []) as $point)
                <text x="{{ $point['x'] - 8 }}" y="306" fill="#9a6c7b" font-size="14">{{ $point['label'] }}</text>
            @endforeach
        </svg>
    </div>
</section>
