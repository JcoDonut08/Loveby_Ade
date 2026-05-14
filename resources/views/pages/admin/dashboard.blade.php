@extends('layouts.admin')

@section('title', 'Dashboard | Loveby_Ade Admin')
@section('description', 'Loveby_Ade admin dashboard overview.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 5.75h8M8 18.25h8M6.75 5.75v12.5M17.25 5.75v12.5" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Dashboard</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Welcome back, Ade - here's how Loveby_Ade is doing today.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6.5" />
                                <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                            </svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-search" type="search" placeholder="Search orders, products, customers...">
                    </label>

                    <button class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                        <span class="absolute -right-0.5 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-[#512438]">3</span>
                    </button>

                    <x-admin.profile-avatar class="h-12 w-12 text-sm shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)]" />
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 lg:px-10">
            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <x-admin.metric-card title="Revenue" value="₱48,290" trend="12.4%" icon="revenue" tone="pink" />
                <x-admin.metric-card title="Orders" value="1,284" trend="8.2%" icon="orders" tone="purple" />
                <x-admin.metric-card title="Pending" value="27" trend="3.1%" icon="pending" tone="amber" />
                <x-admin.metric-card title="Customers" value="642" trend="4.1%" icon="customers" tone="blue" />
                <x-admin.metric-card title="Avg. order" value="₱37.6" trend="2.3%" icon="average" tone="green" direction="down" />
            </section>

            <div class="mt-6">
                <x-admin.restock-alert />
            </div>

            <section class="mt-7 grid gap-6 2xl:grid-cols-[minmax(0,1.45fr)_minmax(22rem,0.7fr)]">
                <x-admin.sales-performance />
                <x-admin.top-desserts />
            </section>

            <section class="mt-7 grid gap-6 2xl:grid-cols-[minmax(0,1.45fr)_minmax(22rem,0.7fr)]">
                <x-admin.user-activity />
                <x-admin.todo-list />
            </section>

            <section class="mt-7 grid gap-6 2xl:grid-cols-[minmax(0,1.45fr)_minmax(22rem,0.7fr)]">
                <x-admin.recent-orders />
                <x-admin.customer-activity />
            </section>
        </main>
    </div>
@endsection
