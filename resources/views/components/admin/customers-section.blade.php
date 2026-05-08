@php
    $summaryCards = [
        ['key' => 'total', 'title' => 'Total Customers', 'tone' => 'border-love-pink-100 bg-white text-love-pink-500'],
        ['key' => 'top_spender', 'title' => 'Top Spenders', 'tone' => 'border-emerald-100 bg-emerald-50 text-emerald-600'],
        ['key' => 'active_today', 'title' => 'Active Today', 'tone' => 'border-love-blue-100 bg-love-blue-100/80 text-[#23445c]'],
        ['key' => 'new_customer', 'title' => 'New Customers', 'tone' => 'border-amber-100 bg-amber-50 text-[#7a4b21]'],
    ];

    $filters = [
        ['key' => 'all', 'label' => 'All'],
        ['key' => 'top_spender', 'label' => 'Top Spender'],
        ['key' => 'active_today', 'label' => 'Active Today'],
        ['key' => 'new_customer', 'label' => 'New Customer'],
    ];
@endphp

<section class="grid gap-6" data-admin-customers>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Customer summary">
        @foreach ($summaryCards as $card)
            <article class="rounded-[1.25rem] border p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] transition hover:-translate-y-0.5 {{ $card['tone'] }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-extrabold uppercase tracking-wide">{{ $card['title'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#3b1728]" data-customer-summary-count="{{ $card['key'] }}">0</p>
                    </div>

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[1rem] bg-white/88 shadow-sm">
                        @switch($card['key'])
                            @case('top_spender')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.75 17.25h8.5M12 6.75v10.5M9.25 9.25A2.75 2.75 0 0 1 12 6.75a2.75 2.75 0 0 1 2.75 2.5c0 1.5-1.25 2.25-2.75 2.75s-2.75 1.25-2.75 2.75" />
                                </svg>
                                @break

                            @case('new_customer')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8v5.5M14.5 10.75H20" />
                                </svg>
                                @break

                            @case('active_today')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 12.5h3l2-5.25 3.5 10.5 2-5.25h2" />
                                </svg>
                                @break

                            @default
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 11.25a2.5 2.5 0 1 0 0-5M16.75 14.25a4.5 4.5 0 0 1 3.5 4" />
                                </svg>
                        @endswitch
                    </span>
                </div>
            </article>
        @endforeach
    </div>

    <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
        <div class="flex flex-col gap-4 border-b border-love-pink-100/80 p-5 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-[#3b1728]">Customer List</h2>
                <p class="mt-1 text-base font-medium text-[#9a6c7b]" data-customer-result-count>Loading customers...</p>
            </div>

            <label class="relative w-full max-w-xl" for="admin-customer-search">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="11" cy="11" r="6.5" />
                        <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                    </svg>
                </span>
                <input class="h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-customer-search" type="search" placeholder="Search customers..." data-customer-search>
            </label>
        </div>

        <div class="border-b border-love-pink-100/80 px-5 py-4">
            <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Customer filters">
                @foreach ($filters as $filter)
                    <button class="inline-flex h-10 shrink-0 items-center gap-2 rounded-full px-4 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $loop->first ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]' : 'border border-love-pink-100 bg-love-cream text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" type="button" role="tab" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" data-customer-filter="{{ $filter['key'] }}">
                        <span>{{ $filter['label'] }}</span>
                        <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-white/70 px-2 text-xs text-[#512438]" data-customer-filter-count="{{ $filter['key'] }}">0</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid gap-3 p-4" data-customer-list></div>

        <div class="hidden px-5 pb-6" data-customer-empty>
            <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-8 text-center">
                <p class="text-base font-extrabold text-[#512438]">No customers match this view.</p>
                <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Try another filter or search term.</p>
            </div>
        </div>

        <nav class="flex flex-col gap-4 border-t border-love-pink-100 px-5 py-4 xl:flex-row xl:items-center xl:justify-between" aria-label="Customer list pagination">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5">
                <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-customers-page-size">
                    <span>Customers per page</span>
                    <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-customers-page-size" data-customer-page-size>
                        <option value="3" selected>3 customers</option>
                        <option value="5">5 customers</option>
                        <option value="10">10 customers</option>
                    </select>
                </label>

                <p class="text-sm font-medium text-[#9a6c7b]" data-customer-pagination-status>Showing 0-0 of 0 customers</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-customer-page-previous disabled>
                    Previous
                </button>
                <div class="flex flex-wrap items-center gap-2" data-customer-page-buttons></div>
                <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-customer-page-next disabled>
                    Next
                </button>
            </div>
        </nav>
    </section>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-customer-details aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close customer details" data-customer-details-close></button>

        <section class="relative flex max-h-[calc(100vh-3rem)] w-full max-w-4xl flex-col rounded-[1.25rem] border border-love-pink-100 bg-white shadow-[0_30px_90px_-36px_rgba(59,23,40,0.55)]">
            <div class="flex items-center justify-between gap-4 border-b border-love-pink-100 px-5 py-4">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Customer Profile</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-[#3b1728]" data-customer-details-title>Customer</h2>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close customer details" data-customer-details-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-5" data-customer-details-content></div>
        </section>
    </div>
</section>
