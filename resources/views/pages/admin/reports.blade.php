@extends('layouts.admin')

@section('title', 'Reports | Loveby_Ade Admin')
@section('description', 'Export and share business reports.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h8.5l2 2v12.5H6.75V4.75Z" /><path stroke-linecap="round" d="M10 14.5v2M12.5 12.5v4M15 10.5v6" /></svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Reports</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Export and share business reports.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-report-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6.5" /><path stroke-linecap="round" d="m16 16 4.5 4.5" /></svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-report-search" type="search" placeholder="Search orders, products, customers...">
                    </label>

                    <button class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" /></svg>
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

        <main class="px-4 py-7 sm:px-6 lg:px-10" data-admin-reports>
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Generate reports</h2>
                        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Pick a date range and download in your favorite format</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="report-from-date">From<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="report-from-date" type="date"></label>
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="report-to-date">To<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="report-to-date" type="date"></label>
                    </div>
                </div>
            </section>

            <section class="mt-6 grid gap-6 xl:grid-cols-2">
                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                    <div class="flex items-start gap-4">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[1rem] bg-love-pink-400 text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M16.25 8.25H10.5a2.25 2.25 0 0 0 0 4.5h3a2.25 2.25 0 0 1 0 4.5H7.75" /></svg></span>
                        <div class="min-w-0">
                            <h3 class="text-xl font-extrabold text-[#3b1728]">Sales report</h3>
                            <p class="mt-2 text-sm font-medium text-[#9a6c7b]">Revenue, orders and AOV across periods</p>
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-wrap gap-2">
                            <button class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">PDF</button>
                            <button class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">Excel</button>
                        </div>
                        <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">Download</button>
                    </div>
                </article>

                <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                    <div class="flex items-start gap-4">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[1rem] bg-love-orange-400 text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13.2A7.9 7.9 0 1 1 10.8 4 4 4 0 0 0 15 8.2 4 4 0 0 0 20 13.2Z" /><path stroke-linecap="round" d="M8.5 11h.01M12 15h.01M8.25 16.5h.01" /></svg></span>
                        <div class="min-w-0">
                            <h3 class="text-xl font-extrabold text-[#3b1728]">Product performance</h3>
                            <p class="mt-2 text-sm font-medium text-[#9a6c7b]">Top-selling desserts and stock turnover</p>
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-wrap gap-2">
                            <button class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">PDF</button>
                            <button class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">Excel</button>
                        </div>
                        <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">Download</button>
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
