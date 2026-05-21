@props([
    'count' => null,
])

@php
    $notificationCount = $count ?? app(\App\Services\AdminNotificationService::class)->unreadCount(session('read_admin_notifications', []));
@endphp

<a class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ route('admin.notifications') }}" aria-label="View notifications">
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
    </svg>

    @if ($notificationCount > 0)
        <span class="absolute -right-0.5 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-[#512438]">{{ $notificationCount }}</span>
    @endif
</a>
