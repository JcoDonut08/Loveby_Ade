@extends('layouts.admin')

@section('title', 'Notifications | Loveby_Ade Admin')
@section('description', 'Review important shop updates, inventory alerts, orders, and customer activity.')

@section('content')
    @php
        $notifications = ($notifications ?? collect())->values();
        $unreadCount = $unreadCount ?? $notifications->where('unread', true)->count();
        $totalCount = $totalCount ?? $notifications->count();
    @endphp

    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]" data-admin-notifications>
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Notifications</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Everything happening across your shop.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-notification-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6.5" />
                                <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                            </svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-notification-search" type="search" placeholder="Search orders, products, customers..." data-notification-search>
                    </label>

                    <x-admin.notification-link :count="$unreadCount" />

                    <div class="flex h-12 shrink-0 items-center gap-3 rounded-full border border-love-pink-100 bg-white/88 py-1 pl-1 pr-4 shadow-[0_18px_35px_-28px_rgba(81,36,56,0.35)]">
                        <x-admin.profile-avatar class="h-10 w-10 text-sm" />
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-extrabold leading-tight text-[#512438]">{{ auth()->user()?->name ?? 'Admin' }}</span>
                            <span class="block text-xs font-medium leading-tight text-[#9a6c7b]">Admin</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 lg:px-10">
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Notification center</h2>
                        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">{{ $unreadCount }} unread - {{ $totalCount }} total</p>
                    </div>

                    @if ($totalCount > 0)
                        <form method="POST" action="{{ route('admin.notifications.read') }}">
                            @csrf
                            <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100 disabled:cursor-not-allowed disabled:opacity-50" type="submit" @disabled($unreadCount === 0)>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />
                                </svg>
                                Mark all read
                            </button>
                        </form>
                    @endif
                </div>

                @if (session('status'))
                    <p class="mt-4 rounded-[1rem] bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">{{ session('status') }}</p>
                @endif
            </section>

            @if ($notifications->isEmpty())
                <section class="mt-5 rounded-[1.25rem] border border-dashed border-love-pink-200 bg-white/90 p-8 text-center shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-notification-empty>
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-[1rem] bg-love-pink-100 text-love-pink-500">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                    </span>
                    <h3 class="mt-4 text-xl font-extrabold text-[#3b1728]">No notifications yet</h3>
                    <p class="mt-2 text-sm font-medium text-[#9a6c7b]">New orders, low stock products, customer signups, and product reviews will show up here.</p>
                </section>
            @else
                <section class="mt-5 grid gap-3" data-notification-list>
                    @foreach ($notifications as $notification)
                        @php
                            $toneClasses = match ($notification['tone']) {
                                'orange' => 'bg-love-orange-400 text-[#512438]',
                                'purple' => 'bg-[#c084fc] text-white',
                                'blue' => 'bg-love-blue-300 text-[#17324d]',
                                'green' => 'bg-[#4ade80] text-white',
                                'rose' => 'bg-rose-400 text-white',
                                'cream' => 'bg-love-cream text-[#512438]',
                                default => 'bg-love-pink-400 text-white',
                            };
                        @endphp

                        <article class="grid gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)] transition hover:-translate-y-0.5 sm:grid-cols-[3.5rem_minmax(0,1fr)_auto] sm:items-center {{ $notification['unread'] ? 'border-l-4 border-love-pink-400' : 'border border-love-pink-100/70' }}" data-notification-row data-notification-search-text="{{ str($notification['title'].' '.$notification['message'])->lower() }}">
                            <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] {{ $toneClasses }}">
                                @switch($notification['icon'])
                                    @case('alert')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25 20 18.75H4L12 5.25Z" /><path stroke-linecap="round" d="M12 10.5v3.25M12 16.75h.01" /></svg>
                                        @break

                                    @case('user')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0M17.25 8.75v5.5M14.5 11.5H20" /></svg>
                                        @break

                                    @case('delivery')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h9.5v8.5h-9.5zM15.25 10.25h2.5l2.5 3v3h-5M8.5 19.25h.01M17.5 19.25h.01" /></svg>
                                        @break

                                    @case('check')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 12.5 10 16.75 18.25 7.25" /></svg>
                                        @break

                                    @case('cancelled')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m7.75 7.75 8.5 8.5M16.25 7.75l-8.5 8.5" /></svg>
                                        @break

                                    @case('review')
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                                        @break

                                    @default
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" /></svg>
                                @endswitch
                            </span>

                            <span class="min-w-0">
                                <strong class="block truncate text-base text-[#3b1728]">{{ $notification['title'] }}</strong>
                                <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">{{ $notification['message'] }}</span>
                            </span>

                            <span class="flex flex-wrap items-center gap-2 sm:justify-end">
                                <span class="w-full text-xs font-semibold text-[#9a6c7b] sm:text-right">{{ $notification['time'] }}</span>
                                <a class="inline-flex h-9 items-center justify-center rounded-full border border-love-pink-100 px-4 text-xs font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $notification['url'] }}">View</a>

                                @if ($notification['unread'])
                                    <form method="POST" action="{{ route('admin.notifications.read-one', $notification['id']) }}">
                                        @csrf
                                        <button class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-love-pink-100 text-love-pink-500 transition hover:bg-love-pink-400 hover:text-white focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Mark notification as read">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </span>
                        </article>
                    @endforeach
                </section>

                <section class="mt-5 hidden rounded-[1.25rem] border border-dashed border-love-pink-200 bg-white/90 p-8 text-center shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-notification-search-empty>
                    <h3 class="text-xl font-extrabold text-[#3b1728]">No matching notifications</h3>
                    <p class="mt-2 text-sm font-medium text-[#9a6c7b]">Try another order number, customer name, or product title.</p>
                </section>

                <section class="mt-5 flex flex-col gap-4 rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-4 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-notifications-page-size">
                            Rows per page
                            <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-notifications-page-size" data-notification-page-size>
                                <option value="6">6 rows</option>
                                <option value="9">9 rows</option>
                                <option value="12">12 rows</option>
                            </select>
                        </label>
                        <p class="text-sm font-semibold text-[#9a6c7b]" data-notification-pagination-status>Showing 1-{{ min(6, $totalCount) }} of {{ $totalCount }} notifications</p>
                    </div>

                    <nav class="flex flex-wrap items-center gap-2" aria-label="Notification pagination">
                        <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-notification-page-previous>Previous</button>
                        <span class="flex flex-wrap items-center gap-2" data-notification-page-buttons></span>
                        <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-notification-page-next>Next</button>
                    </nav>
                </section>
            @endif
        </main>
    </div>
@endsection
