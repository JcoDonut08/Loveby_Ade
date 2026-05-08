@php
    $summaryCards = [
        ['key' => 'pending', 'title' => 'Pending Orders', 'tone' => 'border-amber-100 bg-amber-50 text-amber-700'],
        ['key' => 'preparing', 'title' => 'Mark for Delivery', 'tone' => 'border-love-pink-100 bg-love-pink-100/70 text-love-pink-500'],
        ['key' => 'out_for_delivery', 'title' => 'Out for Delivery', 'tone' => 'border-love-blue-100 bg-love-blue-100/80 text-[#23445c]'],
        ['key' => 'delivered', 'title' => 'Delivered Orders', 'tone' => 'border-emerald-100 bg-emerald-50 text-emerald-600'],
        ['key' => 'cancelled', 'title' => 'Cancelled Orders', 'tone' => 'border-rose-100 bg-rose-50 text-rose-500'],
    ];

    $filters = [
        ['key' => 'all', 'label' => 'All'],
        ['key' => 'pending', 'label' => 'Pending'],
        ['key' => 'preparing', 'label' => 'Preparing'],
        ['key' => 'out_for_delivery', 'label' => 'Out for Delivery'],
        ['key' => 'delivered', 'label' => 'Delivered'],
        ['key' => 'cancelled', 'label' => 'Cancelled'],
    ];

    $quickReasons = [
        'Product unavailable',
        'Invalid order details',
        'Customer requested cancellation',
        'Shop cannot fulfill order',
        'Duplicate order',
    ];
@endphp

<section class="grid gap-6" data-admin-order-management>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5" aria-label="Order status summary">
        @foreach ($summaryCards as $card)
            <article class="rounded-[1.25rem] border p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] transition hover:-translate-y-0.5 {{ $card['tone'] }}" data-order-summary-card="{{ $card['key'] }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-extrabold uppercase tracking-wide">{{ $card['title'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#3b1728]" data-order-summary-count="{{ $card['key'] }}">0</p>
                    </div>

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[1rem] bg-white/88 shadow-sm">
                        @switch($card['key'])
                            @case('pending')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v6l3.5 2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                                @break

                            @case('preparing')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.25h11M7.75 10.25c-.5-3 1.55-5.5 4.25-5.5 1.35 0 2.4.5 3.1 1.35.35-.2.8-.35 1.4-.35 1.8 0 3.25 1.45 3.25 3.25 0 .45-.1.88-.25 1.25" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m7.25 10.25 1.05 8h7.4l1.05-8M10 13.25h4M10.5 16h3" />
                                </svg>
                                @break

                            @case('out_for_delivery')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z" />
                                </svg>
                                @break

                            @case('delivered')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.25 10.9 15l4.85-5.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                                @break

                            @default
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.25 9.25 5.5 5.5M14.75 9.25l-5.5 5.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                </svg>
                        @endswitch
                    </span>
                </div>
            </article>
        @endforeach
    </div>

    <section id="orders" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
        <div class="flex flex-col gap-4 border-b border-love-pink-100/80 p-5 xl:flex-row xl:items-start xl:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-[#3b1728]">Customer Dessert Orders</h2>
                <p class="mt-1 text-base font-medium text-[#9a6c7b]" data-order-result-count>Loading orders...</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-add-order>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                    </svg>
                    <span>Add Order</span>
                </button>

                <div class="flex items-center gap-3 rounded-2xl border border-love-pink-100 bg-love-cream px-4 py-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-extrabold text-[#3b1728]">New pending order</p>
                        <p class="mt-0.5 text-xs font-medium text-[#9a6c7b]">#LBA-3508 came in today</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-love-pink-100/80 px-5 py-4">
            <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Order status filters">
                @foreach ($filters as $filter)
                    <button class="inline-flex h-10 shrink-0 items-center gap-2 rounded-full px-4 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $loop->first ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]' : 'border border-love-pink-100 bg-white text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" type="button" role="tab" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" data-order-filter="{{ $filter['key'] }}">
                        <span>{{ $filter['label'] }}</span>
                        <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-white/70 px-2 text-xs text-[#512438]" data-order-filter-count="{{ $filter['key'] }}">0</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto px-4 pb-5">
            <table id="admin-orders-table" class="w-full min-w-[76rem] border-separate border-spacing-y-3 text-left text-sm">
                <thead>
                    <tr class="text-[#9a6c7b]">
                        <th class="px-4 py-3 font-extrabold">Order ID</th>
                        <th class="px-4 py-3 font-extrabold">Customer Name</th>
                        <th class="px-4 py-3 font-extrabold">Products</th>
                        <th class="px-4 py-3 font-extrabold">Quantity</th>
                        <th class="px-4 py-3 font-extrabold">Total Amount</th>
                        <th class="px-4 py-3 font-extrabold">Date Ordered</th>
                        <th class="px-4 py-3 text-center font-extrabold">Status</th>
                        <th class="px-4 py-3 font-extrabold">Actions</th>
                    </tr>
                </thead>
                <tbody data-order-table-body></tbody>
            </table>
        </div>

        <div class="hidden px-5 pb-6" data-order-empty>
            <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-8 text-center">
                <p class="text-base font-extrabold text-[#512438]">No orders match this view.</p>
                <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Try another filter or search term.</p>
            </div>
        </div>

        <nav class="flex flex-col gap-4 border-t border-love-pink-100 px-5 py-4 xl:flex-row xl:items-center xl:justify-between" aria-label="Order table pagination">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5">
                <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-orders-page-size">
                    <span>Rows per page</span>
                    <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-orders-page-size" data-order-page-size aria-controls="admin-orders-table">
                        <option value="5" selected>5 rows</option>
                        <option value="10">10 rows</option>
                        <option value="20">20 rows</option>
                    </select>
                </label>

                <p class="text-sm font-medium text-[#9a6c7b]" data-order-pagination-status>Showing 0-0 of 0 orders</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-order-page-previous disabled>
                    Previous
                </button>
                <div class="flex flex-wrap items-center gap-2" data-order-page-buttons></div>
                <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 disabled:cursor-not-allowed disabled:opacity-50" type="button" data-order-page-next disabled>
                    Next
                </button>
            </div>
        </nav>
    </section>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-add-order-modal aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close add order form" data-add-order-close></button>

        <section class="relative max-h-[calc(100vh-3rem)] w-full max-w-2xl overflow-y-auto rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#3b1728]">Add Order</h2>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Record a walk-in dessert order for this table.</p>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close add order form" data-add-order-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <form class="mt-6 grid gap-5" data-add-order-form>
                <label class="block" for="walk-in-order-id">
                    <span class="text-sm font-extrabold text-[#512438]">Order ID</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none" id="walk-in-order-id" type="text" data-add-order-id readonly>
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block" for="walk-in-customer-name">
                        <span class="text-sm font-extrabold text-[#512438]">Customer name</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-customer-name" type="text" placeholder="Customer name" data-add-customer-name required>
                    </label>

                    <label class="block" for="walk-in-product">
                        <span class="text-sm font-extrabold text-[#512438]">Product</span>
                        <select class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-product" data-add-product required></select>
                    </label>

                    <label class="block" for="walk-in-quantity">
                        <span class="text-sm font-extrabold text-[#512438]">Quantity</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-quantity" type="number" min="1" value="1" data-add-quantity required>
                    </label>

                    <label class="block" for="walk-in-total-amount">
                        <span class="text-sm font-extrabold text-[#512438]">Total amount</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-total-amount" type="number" min="0" step="0.01" data-add-total required>
                    </label>
                </div>

                <label class="block" for="walk-in-date">
                    <span class="text-sm font-extrabold text-[#512438]">Date ordered</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-date" type="datetime-local" data-add-date required>
                </label>

                <p class="hidden text-sm font-bold text-rose-500" data-add-order-validation>Please complete the walk-in order details.</p>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-add-order-close>
                        Cancel
                    </button>
                    <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">
                        Add Order
                    </button>
                </div>
            </form>
        </section>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-cancel-modal aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Keep order" data-cancel-close></button>

        <section class="relative w-full max-w-lg rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#3b1728]">Cancel Order</h2>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Why are you cancelling this order?</p>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Keep order" data-cancel-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($quickReasons as $reason)
                    <button class="rounded-full border border-love-pink-100 bg-love-cream px-3 py-2 text-xs font-extrabold text-[#512438] transition hover:-translate-y-0.5 hover:border-love-pink-300 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-cancel-reason="{{ $reason }}">
                        {{ $reason }}
                    </button>
                @endforeach
            </div>

            <label class="mt-5 block" for="cancel-order-reason">
                <span class="text-sm font-extrabold text-[#512438]">Cancellation reason</span>
                <textarea class="mt-2 min-h-32 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="cancel-order-reason" placeholder="Write a clear note for this cancellation..." data-cancel-reason-input></textarea>
            </label>
            <p class="mt-2 hidden text-sm font-bold text-rose-500" data-cancel-validation>Please provide a cancellation reason.</p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-cancel-close>
                    Keep Order
                </button>
                <button class="inline-flex h-11 items-center justify-center rounded-full bg-rose-500 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(244,63,94,0.9)] transition hover:-translate-y-0.5 hover:bg-rose-600 focus:outline-none focus:ring-4 focus:ring-rose-100" type="button" data-cancel-confirm>
                    Confirm Cancellation
                </button>
            </div>
        </section>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-order-details aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close order details" data-details-close></button>

        <section class="relative flex max-h-[calc(100vh-3rem)] w-full max-w-3xl flex-col rounded-[1.25rem] border border-love-pink-100 bg-white shadow-[0_30px_90px_-36px_rgba(59,23,40,0.55)]" data-order-details-panel>
            <div class="flex items-center justify-between gap-4 border-b border-love-pink-100 px-5 py-4">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Order Details</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-[#3b1728]" data-details-title>Order</h2>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close order details" data-details-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-5" data-order-details-content></div>
        </section>
    </div>

    <div class="fixed right-4 top-4 z-[60] grid w-[calc(100%-2rem)] max-w-sm gap-3 sm:right-6 sm:top-6" data-order-toast-region aria-live="polite" aria-relevant="additions"></div>
</section>
