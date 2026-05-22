@extends('layouts.admin')

@section('title', 'Analytics | Loveby_Ade Admin')
@section('description', 'Review sales, customer ordering behavior, dessert performance, and stock turnover.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 18.25V11.5M12 18.25V5.75M18.25 18.25v-9" />
                            <path stroke-linecap="round" d="M4.25 19.25h15.5" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Analytics</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Sales reports, customer orders, top desserts, and stock turnover.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <form class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" method="GET" action="{{ route('admin.analytics') }}">
                        <input type="hidden" name="period" value="{{ $filters['period'] }}">
                        <label class="sr-only" for="admin-analytics-search">Search analytics</label>
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6.5" /><path stroke-linecap="round" d="m16 16 4.5 4.5" /></svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-analytics-search" name="search" type="search" value="{{ $filters['search'] }}" placeholder="Search reports, customers, desserts...">
                    </form>

                    <x-admin.notification-link />

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

        <main class="px-4 py-4 sm:px-6 lg:px-10" data-admin-analytics>
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Date range</h2>
                        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Filter analytics by reporting period.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $activePeriodClasses = 'inline-flex h-10 items-center rounded-full bg-love-pink-400 px-4 text-sm font-extrabold text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]';
                            $inactivePeriodClasses = 'inline-flex h-10 items-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500';
                        @endphp
                        @foreach ($periods as $period)
                            <a class="{{ $period['active'] ? $activePeriodClasses : $inactivePeriodClasses }}" href="{{ $period['url'] }}" @if ($period['active']) aria-current="true" @endif>{{ $period['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="mt-6 grid gap-4 lg:grid-cols-2 2xl:grid-cols-4">
                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]"><div class="flex items-start justify-between gap-4"><div><p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Total Revenue</p><p class="mt-4 text-3xl font-extrabold text-[#3b1728]">&#8369;{{ $summary['revenue']['amount'] }}</p><p class="mt-1 text-sm font-semibold text-[#9a6c7b]">Total sales amount</p></div><span class="flex h-14 w-14 items-center justify-center rounded-[1.15rem] bg-love-pink-400 text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M12 5v14M16.25 8.25H10.5a2.25 2.25 0 0 0 0 4.5h3a2.25 2.25 0 0 1 0 4.5H7.75" /></svg></span></div><p class="mt-5 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-extrabold text-emerald-600">{{ $summary['revenue']['trend'] }}</p></article>
                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]"><div class="flex items-start justify-between gap-4"><div><p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Total Orders</p><p class="mt-4 text-3xl font-extrabold text-[#3b1728]">{{ $summary['orders']['count'] }}</p><p class="mt-1 text-sm font-semibold text-[#9a6c7b]">{{ $summary['orders']['detail'] }}</p></div><span class="flex h-14 w-14 items-center justify-center rounded-[1.15rem] bg-[#c084fc] text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" /></svg></span></div><p class="mt-5 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-extrabold text-emerald-600">{{ $summary['orders']['trend'] }}</p></article>
                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]"><div class="flex items-start justify-between gap-4"><div><p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Total Customers</p><p class="mt-4 text-3xl font-extrabold text-[#3b1728]">{{ $summary['customers']['count'] }}</p><p class="mt-1 text-sm font-semibold text-[#9a6c7b]">{{ $summary['customers']['detail'] }}</p></div><span class="flex h-14 w-14 items-center justify-center rounded-[1.15rem] bg-love-blue-300 text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0M15.75 11.25a2.5 2.5 0 1 0 0-5M16.75 14.25a4.5 4.5 0 0 1 3.5 4" /></svg></span></div><p class="mt-5 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-extrabold text-emerald-600">{{ $summary['customers']['trend'] }}</p></article>
                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]"><div class="flex items-start gap-4"><img class="h-16 w-16 rounded-[1rem] object-cover ring-1 ring-love-pink-100" src="{{ $summary['bestProduct']['image'] }}" alt="{{ $summary['bestProduct']['title'] }}"><div class="min-w-0 flex-1"><p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Best-Selling Product</p><p class="mt-2 truncate text-xl font-extrabold text-[#3b1728]">{{ $summary['bestProduct']['title'] }}</p><p class="mt-1 text-sm font-semibold text-[#9a6c7b]">{{ $summary['bestProduct']['sold'] }}</p></div><span class="flex h-11 w-11 items-center justify-center rounded-[1rem] bg-love-gold-300 text-[#512438]"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 4.75 2.15 4.35 4.8.7-3.47 3.38.82 4.77L12 15.7l-4.3 2.25.82-4.77L5.05 9.8l4.8-.7L12 4.75Z" /></svg></span></div><p class="mt-5 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-extrabold text-emerald-600">{{ $summary['bestProduct']['trend'] }}</p></article>
            </section>

            <section class="mt-6 grid gap-6 2xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
                <x-admin.analytics-sales-table :rows="$salesRows" />
                <x-admin.analytics-product-table :rows="$productRows" />
            </section>
        </main>
    </div>
@endsection
