@props([
    'order',
    'stages',
    'showProgress' => true,
    'showInvoice' => true,
    'showEdit' => true,
    'showConfirm' => false,
])

@php
    $isCancelled = $order['status'] === 'cancelled';
    $canConfirmDelivery = $order['status'] === 'out_for_delivery';
@endphp

<article class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_18px_40px_-32px_rgba(15,23,42,0.18)]" data-customer-order-card data-order-status="{{ $order['status'] }}">
    <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <h2 class="font-display text-3xl text-slate-950">{{ $order['id'] }}</h2>
            @if ($showInvoice)
                <a class="text-sm font-semibold text-love-pink-500 transition hover:text-love-pink-400" href="{{ route('orders.receipt', $order['key']) }}">
                    View invoice ->
                </a>
            @endif
        </div>

        <p class="text-sm font-medium text-slate-500">Order placed <span class="font-extrabold text-slate-950">{{ $order['placed_at'] }}</span></p>
    </div>

    <div class="grid gap-6 px-5 py-6 lg:grid-cols-[10rem_minmax(0,1.7fr)_minmax(0,0.8fr)_minmax(0,0.8fr)] lg:items-start">
        <img class="aspect-square w-32 rounded-[1rem] bg-slate-100 object-cover lg:w-36" src="{{ $order['featured_image'] }}" alt="{{ $order['featured_name'] }}">

        <div class="min-w-0">
            <h3 class="text-[1.9rem] font-extrabold text-slate-950">{{ $order['featured_name'] }}</h3>
            <p class="mt-2 text-base font-semibold text-slate-950">{!! $order['total'] !!}</p>
            <p class="mt-2 text-sm font-medium text-slate-500">Quantity: {{ $order['quantity'] }}</p>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-500">{{ $order['description'] }}</p>
        </div>

        <div class="grid gap-3">
            <div>
                <p class="text-sm font-extrabold text-slate-950">Delivery address</p>
                <div class="mt-3 text-sm leading-7 text-slate-500">
                    <p>{{ $order['recipient'] }}</p>
                    @foreach ($order['delivery_lines'] as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-3">
            <div>
                <p class="text-sm font-extrabold text-slate-950">Shipping updates</p>
                <div class="mt-3 text-sm leading-7 text-slate-500">
                    <p>{{ $order['update_email'] }}</p>
                    <p>{{ $order['update_phone'] }}</p>
                </div>
                @if ($showEdit)
                    <a class="mt-2 inline-flex text-sm font-semibold text-love-pink-500 transition hover:text-love-pink-400" href="{{ route('contact') }}">
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($showProgress)
        <div class="px-5 py-6">
            @if ($isCancelled)
                <div class="rounded-[1rem] border border-rose-200 bg-rose-50 px-4 py-4">
                    <p class="text-sm font-extrabold text-rose-600">{{ $order['cancelled_copy'] }}</p>
                </div>
            @else
                <div class="grid grid-cols-4 gap-2" data-order-progress>
                    @foreach ($stages as $stage)
                        <span class="h-2 rounded-full {{ $stage['step'] <= $order['current_step'] ? 'bg-love-pink-500' : 'bg-slate-200' }}" data-order-progress-segment></span>
                    @endforeach
                </div>

                <ol class="mt-6 grid gap-3 text-sm sm:grid-cols-4 sm:text-center">
                    @foreach ($stages as $stage)
                        <li class="{{ $stage['step'] <= $order['current_step'] ? 'font-semibold text-love-pink-500' : 'font-medium text-slate-700' }}">
                            {{ $stage['label'] }}
                        </li>
                    @endforeach
                </ol>

                @if ($showConfirm)
                    <form class="mt-6" method="POST" action="{{ route('orders.confirm-delivery', $order['key']) }}">
                        @csrf
                        @method('PATCH')
                        <button class="inline-flex h-12 w-full items-center justify-center rounded-full px-5 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 sm:w-auto {{ $canConfirmDelivery ? 'bg-love-pink-400 text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] hover:-translate-y-0.5 hover:bg-love-pink-500' : 'cursor-not-allowed border border-slate-200 bg-slate-100 text-slate-400' }}" type="submit" @disabled(! $canConfirmDelivery)>
                            Confirm Order
                        </button>
                    </form>
                @endif
            @endif
        </div>
    @elseif ($isCancelled)
        <div class="px-5 py-6">
            <div class="rounded-[1rem] border border-rose-200 bg-rose-50 px-4 py-4">
                <p class="text-sm font-extrabold text-rose-600">{{ $order['cancelled_copy'] }}</p>
            </div>
        </div>
    @endif
</article>
