@extends('layouts.admin')

@section('title', 'Notifications | Loveby_Ade Admin')
@section('description', 'Review important shop updates, inventory alerts, orders, and customer activity.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
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
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-notification-search" type="search" placeholder="Search orders, products, customers...">
                    </label>

                    <button class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                        <span class="absolute -right-0.5 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-[#512438]">3</span>
                    </button>

                    <div class="flex h-12 shrink-0 items-center gap-3 rounded-full border border-love-pink-100 bg-white/88 py-1 pl-1 pr-4 shadow-[0_18px_35px_-28px_rgba(81,36,56,0.35)]">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">AD</span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-extrabold leading-tight text-[#512438]">Ade Sweet</span>
                            <span class="block text-xs font-medium leading-tight text-[#9a6c7b]">Admin</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 lg:px-10" data-admin-notifications>
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Notification center</h2>
                        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">3 unread - 12 total</p>
                    </div>

                    <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />
                        </svg>
                        Mark all read
                    </button>
                </div>
            </section>

            <section class="mt-5 grid gap-3" data-notification-list>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] border-l-4 border-love-pink-400 bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-pink-400 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">New order received</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">#LBA-3421 from Sophia Laurent - &#8369;84.50</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">2 min ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] border-l-4 border-love-pink-400 bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-orange-400 text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25 20 18.75H4L12 5.25Z" /><path stroke-linecap="round" d="M12 10.5v3.25M12 16.75h.01" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Low stock alert</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Glazed Vanilla Donuts only 8 left</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">12 min ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] border-l-4 border-love-pink-400 bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-[#c084fc] text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v5M12 14.25v5M4.75 12h5M14.25 12h5M7.5 7.5 9.75 9.75M14.25 14.25l2.25 2.25M16.5 7.5l-2.25 2.25M9.75 14.25 7.5 16.5" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Sales milestone hit</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">You crossed &#8369;48,000 in monthly revenue</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">1 hour ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-blue-300 text-[#17324d]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0M17.25 8.75v5.5M14.5 11.5H20" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">New customer registered</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Liam O'Connor joined Loveby_Ade</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">3 hours ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-[#4ade80] text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h14.5v9.5H4.75zM4.75 10.75h14.5" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Payment confirmed</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">&#8369;142.00 received via Credit Card</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">5 hours ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-pink-300 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h9.5v8.5h-9.5zM15.25 10.25h2.5l2.5 3v3h-5M8.5 19.25h.01M17.5 19.25h.01" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Order delivered</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">#LBA-3417 was delivered to Yuki Tanaka</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">Yesterday</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Review received</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Chocolate Dream Cake earned 5 stars</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">Yesterday</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Promo code used</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">WELCOME10 was applied by Amelia Brooks</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">Yesterday</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Custom request updated</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Sophia added a cake message</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">2 days ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Inventory restocked</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Macaron Gift Boxes updated to 42 units</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">2 days ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Refund processed</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">#LBA-3398 refund completed</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">3 days ago</span>
                </article>
                <article class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] items-center gap-4 rounded-[1rem] bg-white p-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.45)]" data-notification-row>
                    <span class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-love-cream text-[#512438]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75zM9.5 8.25h5M9.5 11.75h5M9.5 15.25h3" /></svg>
                    </span>
                    <span class="min-w-0"><strong class="block truncate text-base text-[#3b1728]">Weekly report ready</strong><span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Sales summary is ready for review</span></span>
                    <span class="text-right text-xs font-semibold text-[#9a6c7b]">3 days ago</span>
                </article>
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
                    <p class="text-sm font-semibold text-[#9a6c7b]" data-notification-pagination-status>Showing 1-6 of 12 notifications</p>
                </div>

                <nav class="flex flex-wrap items-center gap-2" aria-label="Notification pagination">
                    <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-notification-page-previous>Previous</button>
                    <span class="flex flex-wrap items-center gap-2" data-notification-page-buttons></span>
                    <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-notification-page-next>Next</button>
                </nav>
            </section>
        </main>
    </div>
@endsection
