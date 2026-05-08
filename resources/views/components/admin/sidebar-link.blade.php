@props([
    'href',
    'label',
    'icon' => 'grid',
    'active' => false,
    'badge' => null,
])

<a class="group flex min-h-11 items-center gap-3 rounded-full px-4 py-2.5 text-base font-bold transition {{ $active ? 'bg-love-pink-400 text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)]' : 'text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" href="{{ $href }}"@if ($active) aria-current="page"@endif>
    <span class="flex h-6 w-6 shrink-0 items-center justify-center">
        @switch($icon)
            @case('bag')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" />
                </svg>
                @break

            @case('cookie')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13.2A7.9 7.9 0 1 1 10.8 4 4 4 0 0 0 15 8.2 4 4 0 0 0 20 13.2Z" />
                    <path stroke-linecap="round" d="M8.5 11h.01M12 15h.01M8.25 16.5h.01" />
                </svg>
                @break

            @case('users')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 11.25a2.5 2.5 0 1 0 0-5M16.75 14.25a4.5 4.5 0 0 1 3.5 4" />
                </svg>
                @break

            @case('tag')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 5.75v6.5l7 7 7-7-7-7h-7Z" />
                    <path stroke-linecap="round" d="M8.25 8.25h.01" />
                </svg>
                @break

            @case('chat')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 18.25 6 14.75a7 7 0 1 1 3.25 3.25l-4.5.25Z" />
                </svg>
                @break

            @case('bell')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                </svg>
                @break

            @case('chart')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 18.25V11.5M12 18.25V5.75M18.25 18.25v-9" />
                    <path stroke-linecap="round" d="M4.25 19.25h15.5" />
                </svg>
                @break

            @case('report')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h8.5l2 2v12.5H6.75V4.75Z" />
                    <path stroke-linecap="round" d="M10 14.5v2M12.5 12.5v4M15 10.5v6" />
                </svg>
                @break

            @case('settings')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.75a3.25 3.25 0 1 1 0 6.5 3.25 3.25 0 0 1 0-6.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.3 13.6.95.74-1.5 2.6-1.14-.46a7 7 0 0 1-1.62.94l-.18 1.23h-3.62l-.18-1.23a7 7 0 0 1-1.62-.94l-1.14.46-1.5-2.6.95-.74a7 7 0 0 1 0-1.86l-.95-.74 1.5-2.6 1.14.46a7 7 0 0 1 1.62-.94l.18-1.23h3.62l.18 1.23a7 7 0 0 1 1.62.94l1.14-.46 1.5 2.6-.95.74a7 7 0 0 1 0 1.86Z" />
                </svg>
                @break

            @default
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 5.5h5v5h-5zM13.5 5.5h5v5h-5zM5.5 13.5h5v5h-5zM13.5 13.5h5v5h-5z" />
                </svg>
        @endswitch
    </span>

    <span class="min-w-0 flex-1 truncate">{{ $label }}</span>

    @if ($badge)
        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-love-blue-300 px-2 text-xs font-extrabold text-[#512438]">
            {{ $badge }}
        </span>
    @endif
</a>
