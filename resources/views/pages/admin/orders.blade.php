@extends('layouts.admin')

@section('title', 'Order Management | Loveby_Ade Admin')
@section('description', 'Track, confirm, prepare, and manage customer dessert orders.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 7.75h12.5l-1 11.5H6.75l-1-11.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.75a3 3 0 0 1 6 0" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Order Management</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Track, confirm, prepare, and manage customer dessert orders.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <form class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" method="GET" action="{{ route('admin.orders') }}">
                        @if (request()->filled('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if (request()->filled('page_size'))
                            <input type="hidden" name="page_size" value="{{ request('page_size') }}">
                        @endif

                        <label for="admin-order-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6.5" />
                                <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                            </svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-order-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search orders, customers, desserts..." data-order-search>
                        </label>
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

        <main class="px-4 py-4 sm:px-6 lg:px-10">
            <x-admin.orders-section :orders="$orders" :status-counts="$statusCounts" :statuses="$statuses" :products="$products" :promotions="$promotions" :walk-in-order-number="$walkInOrderNumber" />
        </main>
    </div>
@endsection
