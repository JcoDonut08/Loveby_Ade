@props([
    'orders',
    'statusCounts',
    'statuses',
    'products',
    'walkInOrderNumber',
])

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
        ['key' => 'walk_in', 'label' => 'Walk-In'],
    ];

    $quickReasons = [
        'Product unavailable',
        'Invalid order details',
        'Customer requested cancellation',
        'Shop cannot fulfill order',
        'Duplicate order',
    ];

    $labels = [
        'pending' => 'Pending',
        'preparing' => 'Preparing',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    $activeStatus = request('status', 'all');
    $allOrderCount = collect($statusCounts)->except('walk_in')->sum();
    $showWalkInModal = $errors->has('order_number') || $errors->has('customer_name') || $errors->has('date_ordered') || $errors->has('products') || $errors->has('products.*.product_id') || $errors->has('products.*.quantity');
    $queryForStatus = function (string $status): array {
        $query = request()->except(['page', 'status']);

        if ($status !== 'all') {
            $query['status'] = $status;
        }

        return $query;
    };

    $statusClass = function (string $status): string {
        return match ($status) {
            'pending' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'preparing' => 'bg-love-pink-100 text-love-pink-500 ring-love-pink-200',
            'out_for_delivery' => 'bg-love-blue-100 text-[#23445c] ring-love-blue-200',
            'delivered' => 'bg-emerald-100 text-emerald-600 ring-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-500 ring-rose-200',
            default => 'bg-love-pink-100 text-love-pink-500 ring-love-pink-200',
        };
    };

    $itemImage = function ($item): string {
        if ($item->product_image) {
            return $item->product_image;
        }

        if ($item->product?->image_path) {
            return Storage::disk('public')->url($item->product->image_path);
        }

        return $item->product?->image_url ?: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=240&q=80';
    };

    $tooltipClass = 'pointer-events-none absolute bottom-full left-1/2 z-20 mb-2 min-w-max -translate-x-1/2 translate-y-1 rounded-lg bg-[#3b1728] px-2.5 py-1 text-xs font-extrabold text-white opacity-0 shadow-lg transition group-hover/status:translate-y-0 group-hover/status:opacity-100 group-focus-visible/status:translate-y-0 group-focus-visible/status:opacity-100 group-hover/action:translate-y-0 group-hover/action:opacity-100 group-focus-visible/action:translate-y-0 group-focus-visible/action:opacity-100';
@endphp

<section class="grid gap-6" data-admin-order-management data-backend-orders="true">
    @if (session('status'))
        <div class="rounded-[1.25rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-extrabold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5" aria-label="Order status summary">
        @foreach ($summaryCards as $card)
            <article class="rounded-[1.25rem] border p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] transition hover:-translate-y-0.5 {{ $card['tone'] }}" data-order-summary-card="{{ $card['key'] }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-extrabold uppercase tracking-wide">{{ $card['title'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#3b1728]" data-order-summary-count="{{ $card['key'] }}">{{ $statusCounts[$card['key']] ?? 0 }}</p>
                    </div>

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[1rem] bg-white/88 shadow-sm">
                        @switch($card['key'])
                            @case('pending')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v6l3.5 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                @break

                            @case('preparing')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.25h11M7.75 10.25c-.5-3 1.55-5.5 4.25-5.5 1.35 0 2.4.5 3.1 1.35.35-.2.8-.35 1.4-.35 1.8 0 3.25 1.45 3.25 3.25 0 .45-.1.88-.25 1.25" /><path stroke-linecap="round" stroke-linejoin="round" d="m7.25 10.25 1.05 8h7.4l1.05-8M10 13.25h4M10.5 16h3" /></svg>
                                @break

                            @case('out_for_delivery')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z" /></svg>
                                @break

                            @case('delivered')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.25 10.9 15l4.85-5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                @break

                            @default
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9.25 9.25 5.5 5.5M14.75 9.25l-5.5 5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
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
                <p class="mt-1 text-base font-medium text-[#9a6c7b]" data-order-result-count>{{ $orders->total() }} of {{ $allOrderCount }} orders shown</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-walk-in-open>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                    </svg>
                    <span>Add Order</span>
                </button>
                <div class="flex items-center gap-3 rounded-2xl border border-love-pink-100 bg-love-cream px-4 py-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" /></svg>
                    </span>
                    <div>
                        <p class="text-sm font-extrabold text-[#3b1728]">New pending order</p>
                        <p class="mt-0.5 text-xs font-medium text-[#9a6c7b]">{{ $statusCounts['pending'] ?? 0 }} pending today</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-love-pink-100/80 px-5 py-4">
            <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Order status filters">
                @foreach ($filters as $filter)
                    @php
                        $isActive = $activeStatus === $filter['key'] || ($filter['key'] === 'all' && ! request()->filled('status'));
                        $filterCount = $filter['key'] === 'all' ? $allOrderCount : ($statusCounts[$filter['key']] ?? 0);
                    @endphp
                    <a class="inline-flex h-10 shrink-0 items-center gap-2 rounded-full px-4 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $isActive ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]' : 'border border-love-pink-100 bg-white text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" href="{{ route('admin.orders', $queryForStatus($filter['key'])) }}" role="tab" aria-pressed="{{ $isActive ? 'true' : 'false' }}" data-order-filter="{{ $filter['key'] }}">
                        <span>{{ $filter['label'] }}</span>
                        <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-white/70 px-2 text-xs text-[#512438]" data-order-filter-count="{{ $filter['key'] }}">{{ $filterCount }}</span>
                    </a>
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
                <tbody data-order-table-body>
                    @forelse ($orders as $order)
                        @php
                            $firstItem = $order->items->first();
                            $remainingItems = max(0, $order->items->count() - 1);
                            $canPrintReceipt = ! in_array($order->status, ['pending', 'cancelled'], true);
                        @endphp
                        <tr class="group/row" data-order-row>
                            <td class="rounded-l-[1.25rem] border-y border-l border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#3b1728] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">#{{ $order->order_number }}</td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-bold text-[#512438] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">{{ $order->full_name }}</td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">
                                @if ($firstItem)
                                    <div class="flex min-w-0 items-center gap-3">
                                        <img class="h-12 w-12 shrink-0 rounded-xl object-cover ring-1 ring-love-pink-100" src="{{ $itemImage($firstItem) }}" alt="{{ $firstItem->product_title }} thumbnail" loading="lazy">
                                        <div class="min-w-0">
                                            <p class="truncate font-extrabold text-[#512438]">{{ $firstItem->product_title }}</p>
                                            <p class="mt-1 text-xs font-bold text-[#9a6c7b]">{{ $remainingItems > 0 ? '+'.$remainingItems.' more' : 'Single dessert' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#512438] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">{{ $order->items->sum('quantity') }}</td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-extrabold text-[#3b1728] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">&#8369;{{ number_format((float) $order->total, 2) }}</td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 font-medium text-[#9a6c7b] shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">{{ $order->created_at?->format('M j, Y, g:i A') }}</td>
                            <td class="border-y border-love-pink-100 bg-white px-4 py-4 text-center shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">
                                <span class="group/status relative inline-flex h-10 w-10 items-center justify-center rounded-full ring-1 transition hover:-translate-y-0.5 {{ $statusClass($order->status) }}" aria-label="{{ $labels[$order->status] ?? ucfirst($order->status) }}" tabindex="0">
                                    @switch($order->status)
                                        @case('pending')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v6l3.5 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                            @break

                                        @case('preparing')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.25h11M7.75 10.25c-.5-3 1.55-5.5 4.25-5.5 1.35 0 2.4.5 3.1 1.35.35-.2.8-.35 1.4-.35 1.8 0 3.25 1.45 3.25 3.25 0 .45-.1.88-.25 1.25" /><path stroke-linecap="round" stroke-linejoin="round" d="m7.25 10.25 1.05 8h7.4l1.05-8M10 13.25h4M10.5 16h3" /></svg>
                                            @break

                                        @case('out_for_delivery')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z" /></svg>
                                            @break

                                        @case('delivered')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.25 10.9 15l4.85-5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                            @break

                                        @default
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                    @endswitch
                                    <span class="{{ $tooltipClass }}">{{ $labels[$order->status] ?? ucfirst($order->status) }}</span>
                                </span>
                            </td>
                            <td class="rounded-r-[1.25rem] border-y border-r border-love-pink-100 bg-white px-4 py-4 shadow-[0_18px_38px_-34px_rgba(81,36,56,0.5)] transition group-hover/row:-translate-y-0.5 group-hover/row:bg-love-cream">
                                <div class="flex min-w-36 flex-wrap gap-2">
                                    @if ($order->is_walk_in && $order->status === 'pending')
                                        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="delivered">
                                            <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-emerald-50 text-emerald-600 transition hover:-translate-y-0.5 hover:bg-emerald-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Mark delivered">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.25 10.9 15l4.85-5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" /></svg>
                                                <span class="{{ $tooltipClass }}">Mark delivered</span>
                                            </button>
                                        </form>
                                        <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-500 transition hover:-translate-y-0.5 hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Cancel order" data-admin-cancel-open data-cancel-action="{{ route('admin.orders.update', $order) }}">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /></svg>
                                            <span class="{{ $tooltipClass }}">Cancel order</span>
                                        </button>
                                    @elseif ($order->status === 'pending')
                                        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="preparing">
                                            <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-emerald-50 text-emerald-600 transition hover:-translate-y-0.5 hover:bg-emerald-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Approve order">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" /></svg>
                                                <span class="{{ $tooltipClass }}">Approve order</span>
                                            </button>
                                        </form>
                                        <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-500 transition hover:-translate-y-0.5 hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Cancel order" data-admin-cancel-open data-cancel-action="{{ route('admin.orders.update', $order) }}">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /></svg>
                                            <span class="{{ $tooltipClass }}">Cancel order</span>
                                        </button>
                                    @elseif ($order->status === 'preparing')
                                        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="out_for_delivery">
                                            <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-love-blue-100 bg-love-blue-100/80 text-[#23445c] transition hover:-translate-y-0.5 hover:bg-love-blue-200 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Mark for delivery">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z" /></svg>
                                                <span class="{{ $tooltipClass }}">Mark for delivery</span>
                                            </button>
                                        </form>
                                    @endif

                                    <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-love-pink-100 bg-white text-[#512438] transition hover:-translate-y-0.5 hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View order details" data-admin-details-open data-details-template="order-details-{{ $order->getKey() }}" data-details-heading="#{{ $order->order_number }}">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 12s2.5-5.25 7.25-5.25S19.25 12 19.25 12 16.75 17.25 12 17.25 4.75 12 4.75 12Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" /></svg>
                                        <span class="{{ $tooltipClass }}">View order details</span>
                                    </button>
                                    @if ($canPrintReceipt)
                                        <a class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-amber-100 bg-amber-50 text-amber-700 transition hover:-translate-y-0.5 hover:bg-amber-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ route('admin.orders.receipt', $order) }}" aria-label="Print receipt">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.25 8.75v-4h9.5v4M7.25 17.25h-1.5a2 2 0 0 1-2-2v-4.5a2 2 0 0 1 2-2h12.5a2 2 0 0 1 2 2v4.5a2 2 0 0 1-2 2h-1.5M7.25 14.25h9.5v5.5h-9.5z" /></svg>
                                            <span class="{{ $tooltipClass }}">Print receipt</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="rounded-[1rem] border border-dashed border-love-pink-200 bg-love-cream p-8 text-center font-bold text-[#9a6c7b]" colspan="8">No orders match this view.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->isEmpty())
            <div class="px-5 pb-6" data-order-empty>
                <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-8 text-center">
                    <p class="text-base font-extrabold text-[#512438]">No orders match this view.</p>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Try another filter or search term.</p>
                </div>
            </div>
        @endif

        <nav class="flex flex-col gap-4 border-t border-love-pink-100 px-5 py-4 xl:flex-row xl:items-center xl:justify-between" aria-label="Order table pagination">
            <form class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5" method="GET" action="{{ route('admin.orders') }}">
                @if (request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if (request()->filled('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-orders-page-size">
                    <span>Rows per page</span>
                    <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-orders-page-size" name="page_size" aria-controls="admin-orders-table" onchange="this.form.submit()">
                        @foreach ([5, 10, 20] as $size)
                            <option value="{{ $size }}" @selected((int) request('page_size', 10) === $size)>{{ $size }} rows</option>
                        @endforeach
                    </select>
                </label>

                <p class="text-sm font-medium text-[#9a6c7b]" data-order-pagination-status>Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</p>
            </form>

            <div class="flex flex-wrap items-center gap-2">
                @if ($orders->onFirstPage())
                    <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Previous</span>
                @else
                    <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $orders->previousPageUrl() }}">Previous</a>
                @endif

                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page === $orders->currentPage())
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-love-pink-400 px-4 text-sm font-extrabold text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]">{{ $page }}</span>
                    @else
                        <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($orders->hasMorePages())
                    <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $orders->nextPageUrl() }}">Next</a>
                @else
                    <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Next</span>
                @endif
            </div>
        </nav>
    </section>

    <div class="fixed inset-0 z-50 {{ $showWalkInModal ? 'flex' : 'hidden' }} items-center justify-center px-4 py-6" data-walk-in-modal aria-hidden="{{ $showWalkInModal ? 'false' : 'true' }}">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close add order form" data-walk-in-close></button>

        <section class="relative max-h-[calc(100vh-3rem)] w-full max-w-3xl overflow-y-auto rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#3b1728]">Add Order</h2>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Record a walk-in dessert order for this table.</p>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close add order form" data-walk-in-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /></svg>
                </button>
            </div>

            <form class="mt-6 grid gap-5" method="POST" action="{{ route('admin.orders.store') }}" data-walk-in-form>
                @csrf

                <label class="block" for="walk-in-order-id">
                    <span class="text-sm font-extrabold text-[#512438]">Order ID</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-order-id" type="text" name="order_number" value="{{ old('order_number', $walkInOrderNumber) }}" readonly>
                    @error('order_number')
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block" for="walk-in-customer-name">
                    <span class="text-sm font-extrabold text-[#512438]">Customer name</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-customer-name" type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Customer name" required>
                    @error('customer_name')
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $message }}</span>
                    @enderror
                </label>

                <div class="grid gap-4" data-walk-in-products>
                    <div class="grid gap-4 rounded-[1.25rem] border border-love-pink-100 bg-white p-4 sm:grid-cols-2" data-walk-in-product-row>
                        <label class="block" for="walk-in-product-0">
                            <span class="text-sm font-extrabold text-[#512438]">Product</span>
                            <select class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-product-0" name="products[0][product_id]" required data-walk-in-product-select>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ (float) $product->price }}" @selected((string) old('products.0.product_id') === (string) $product->id)>{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block" for="walk-in-quantity-0">
                            <span class="text-sm font-extrabold text-[#512438]">Quantity</span>
                            <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-quantity-0" type="number" name="products[0][quantity]" min="1" max="999" step="1" value="{{ old('products.0.quantity', 1) }}" required data-walk-in-quantity>
                        </label>

                        <label class="block sm:col-span-2" for="walk-in-line-total-0">
                            <span class="text-sm font-extrabold text-[#512438]">Total amount</span>
                            <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-line-total-0" type="text" value="0.00" readonly data-walk-in-line-total>
                        </label>
                    </div>
                </div>

                @if ($errors->has('products') || $errors->has('products.*.product_id') || $errors->has('products.*.quantity'))
                    <span class="block text-xs font-bold text-rose-500">{{ $errors->first('products') ?: ($errors->first('products.*.product_id') ?: $errors->first('products.*.quantity')) }}</span>
                @endif

                <div class="flex flex-wrap gap-3">
                    <button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-walk-in-add-product>
                        Add Product
                    </button>
                    <button class="inline-flex h-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 px-4 text-sm font-extrabold text-rose-500 transition hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-walk-in-remove-product>
                        Remove Product
                    </button>
                </div>

                <label class="block" for="walk-in-overall-total">
                    <span class="text-sm font-extrabold text-[#512438]">Total Amount</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-overall-total" type="text" value="0.00" readonly data-walk-in-overall-total>
                </label>

                <label class="block" for="walk-in-date-ordered">
                    <span class="text-sm font-extrabold text-[#512438]">Date ordered</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="walk-in-date-ordered" type="datetime-local" name="date_ordered" value="{{ old('date_ordered', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('date_ordered')
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-walk-in-close>Cancel</button>
                    <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">Add Order</button>
                </div>
            </form>
        </section>
    </div>

    <template data-walk-in-product-template>
        <div class="grid gap-4 rounded-[1.25rem] border border-love-pink-100 bg-white p-4 sm:grid-cols-2" data-walk-in-product-row>
            <label class="block">
                <span class="text-sm font-extrabold text-[#512438]">Product</span>
                <select class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" required data-walk-in-product-select>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ (float) $product->price }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block">
                <span class="text-sm font-extrabold text-[#512438]">Quantity</span>
                <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" type="number" min="1" max="999" step="1" value="1" required data-walk-in-quantity>
            </label>
            <label class="block sm:col-span-2">
                <span class="text-sm font-extrabold text-[#512438]">Total amount</span>
                <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" type="text" value="0.00" readonly data-walk-in-line-total>
            </label>
        </div>
    </template>

    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" data-cancel-modal aria-hidden="true">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Keep order" data-cancel-close></button>

        <section class="relative w-full max-w-lg rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#3b1728]">Cancel Order</h2>
                    <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Why are you cancelling this order?</p>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Keep order" data-cancel-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /></svg>
                </button>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($quickReasons as $reason)
                    <button class="rounded-full border border-love-pink-100 bg-love-cream px-3 py-2 text-xs font-extrabold text-[#512438] transition hover:-translate-y-0.5 hover:border-love-pink-300 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-cancel-reason="{{ $reason }}">{{ $reason }}</button>
                @endforeach
            </div>

            <form class="mt-5" method="POST" data-cancel-form>
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">

                <label class="block" for="cancel-order-reason">
                    <span class="text-sm font-extrabold text-[#512438]">Cancellation reason</span>
                    <textarea class="mt-2 min-h-32 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="cancel-order-reason" name="cancellation_reason" placeholder="Write a clear note for this cancellation..." data-cancel-reason-input required></textarea>
                </label>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-cancel-close>Keep Order</button>
                    <button class="inline-flex h-11 items-center justify-center rounded-full bg-rose-500 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(244,63,94,0.9)] transition hover:-translate-y-0.5 hover:bg-rose-600 focus:outline-none focus:ring-4 focus:ring-rose-100" type="submit">Confirm Cancellation</button>
                </div>
            </form>
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
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" /></svg>
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-5" data-order-details-content></div>
        </section>
    </div>

    @foreach ($orders as $order)
        <template id="order-details-{{ $order->getKey() }}">
            <div class="grid gap-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Order ID</p><p class="mt-2 text-sm font-extrabold text-[#512438]">#{{ $order->order_number }}</p></div>
                    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Customer name</p><p class="mt-2 text-sm font-extrabold text-[#512438]">{{ $order->full_name }}</p></div>
                    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Contact number</p><p class="mt-2 text-sm font-extrabold text-[#512438]">{{ $order->contact_number }}</p></div>
                    <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Date ordered</p><p class="mt-2 text-sm font-extrabold text-[#512438]">{{ $order->created_at?->format('M j, Y, g:i A') }}</p></div>
                </div>
                <div class="rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">Delivery address</p><p class="mt-2 text-sm font-extrabold text-[#512438]">{{ $order->complete_address }}</p></div>
                <section>
                    <h3 class="mb-3 text-base font-extrabold text-[#3b1728]">Ordered products</h3>
                    <ul class="grid gap-3">
                        @foreach ($order->items as $item)
                            <li class="{{ $loop->index >= 2 ? 'hidden' : 'flex' }} items-center justify-between gap-3 rounded-[1.25rem] border border-love-pink-100 bg-white p-3" @if ($loop->index >= 2) data-details-extra-product @endif>
                                <div class="flex min-w-0 items-center gap-3">
                                    <img class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-love-pink-100" src="{{ $itemImage($item) }}" alt="{{ $item->product_title }} thumbnail" loading="lazy">
                                    <div class="min-w-0"><p class="truncate text-sm font-extrabold text-[#512438]">{{ $item->product_title }}</p><p class="mt-1 text-xs font-bold text-[#9a6c7b]">Quantity {{ $item->quantity }}</p></div>
                                </div>
                                <p class="text-sm font-extrabold text-[#3b1728]">&#8369;{{ number_format((float) $item->line_total, 2) }}</p>
                            </li>
                        @endforeach
                    </ul>
                    @if ($order->items->count() > 2)
                        <button class="mt-3 inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-details-show-more>
                            Show {{ $order->items->count() - 2 }} more
                        </button>
                    @endif
                </section>
                <div class="grid gap-3 rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4">
                    <div class="flex items-center justify-between gap-4 text-sm font-bold text-[#9a6c7b]"><span>Subtotal</span><span>&#8369;{{ number_format((float) $order->subtotal, 2) }}</span></div>
                    <div class="flex items-center justify-between gap-4 border-t border-love-pink-100 pt-3 text-lg font-extrabold text-[#3b1728]"><span>Total amount</span><span>&#8369;{{ number_format((float) $order->total, 2) }}</span></div>
                </div>
                @if ($order->status === 'cancelled')
                    <div class="rounded-[1.25rem] border border-rose-100 bg-rose-50 p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-rose-500">Cancellation reason</p><p class="mt-2 text-sm font-extrabold text-[#512438]">{{ $order->cancellation_reason ?: 'No reason recorded.' }}</p></div>
                @endif
            </div>
        </template>
    @endforeach

    <div class="fixed right-4 top-4 z-[60] grid w-[calc(100%-2rem)] max-w-sm gap-3 sm:right-6 sm:top-6" data-order-toast-region aria-live="polite" aria-relevant="additions"></div>
</section>
