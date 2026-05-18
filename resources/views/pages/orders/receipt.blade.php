@extends('layouts.guest')

@section('title', 'Receipt '.$order->order_number.' | Loveby_Ade')
@section('description', 'Printable Loveby_Ade order receipt.')
@section('body_classes', 'bg-[#fff5f8] text-slate-950 print:bg-white')

@php
    $detailItems = [
        ['icon' => 'M8 7.75h8M8 11.75h8M8 15.75h5M6.75 4.75h10.5v14.5H6.75z', 'label' => 'Order ID', 'value' => $order->order_number, 'tone' => 'pink'],
        ['icon' => 'M7.75 4.75v3M16.25 4.75v3M5.75 8.25h12.5M6.75 6.25h10.5a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H6.75a1 1 0 0 1-1-1v-10a1 1 0 0 1 1-1Z', 'label' => 'Date', 'value' => $orderedAt, 'tone' => 'pink'],
        ['icon' => 'M12 12.25a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5ZM5.75 19.25a6.25 6.25 0 0 1 12.5 0', 'label' => 'Customer Name', 'value' => $order->full_name, 'tone' => 'pink'],
        ['icon' => 'M5.75 7.25h12.5v9.5H5.75zM6.25 7.75 12 12.25l5.75-4.5', 'label' => 'Email', 'value' => $order->email_address, 'tone' => 'pink'],
        ['icon' => 'M12 12.25a2.75 2.75 0 1 0 0-5.5 2.75 2.75 0 0 0 0 5.5ZM18.25 9.5c0 5-6.25 9.75-6.25 9.75S5.75 14.5 5.75 9.5a6.25 6.25 0 1 1 12.5 0Z', 'label' => 'Delivery Address', 'value' => $order->complete_address, 'tone' => 'blue'],
        ['icon' => 'M5.75 8.25h12.5v7.5H5.75zM7.75 11.25h3.5M7.75 13.75h2', 'label' => 'Payment Method', 'value' => $order->payment_method, 'tone' => 'blue'],
        ['icon' => 'm8.25 12.25 2.5 2.5 5-5M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z', 'label' => 'Payment Status', 'value' => $isPaid ? 'Paid' : 'Payment pending', 'tone' => 'blue'],
        ['icon' => 'M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5zM8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z', 'label' => 'Order Status', 'value' => $statusLabel, 'tone' => 'blue'],
    ];
@endphp

@section('content')
    <div class="min-h-screen py-5 print:min-h-0 print:bg-white print:py-0" data-order-receipt>
        <div class="mx-auto grid w-full max-w-3xl px-4 sm:px-6 print:max-w-none print:px-0">
            <main class="overflow-hidden rounded-[1.25rem] border border-white bg-white shadow-[0_24px_70px_-34px_rgba(81,36,56,0.5)] print:rounded-none print:border-0 print:shadow-none">
                <header class="relative px-5 pb-4 pt-5 text-center sm:px-7 print:px-5 print:pb-3 print:pt-4">
                    <div class="mx-auto flex w-full max-w-2xl items-center justify-center gap-3">
                        <img class="h-16 w-16 shrink-0 rounded-full object-contain print:h-14 print:w-14" src="{{ asset('images/lovebyadelogo.png') }}" alt="Loveby_Ade logo">
                        <div class="min-w-0 text-left">
                            <p class="font-display text-4xl font-bold leading-none text-love-pink-400 sm:text-5xl print:text-4xl">Loveby_Ade</p>
                            <p class="mt-2 text-center text-[10px] font-extrabold uppercase tracking-[0.34em] text-[#512438] print:text-[9px]">Sweet treats, made with love</p>
                        </div>
                    </div>

                    <div class="mx-auto mt-4 flex max-w-2xl items-center gap-3 text-love-pink-300 print:mt-3">
                        <span class="h-px flex-1 border-t border-dashed border-love-pink-300"></span>
                        <span class="text-base">&hearts;</span>
                        <span class="h-px flex-1 border-t border-dashed border-love-pink-300"></span>
                    </div>

                    <div class="mx-auto mt-4 flex max-w-sm items-center justify-center gap-2 bg-love-pink-100 px-4 py-2 text-center text-[#2f3542] [clip-path:polygon(0_0,100%_0,94%_50%,100%_100%,0_100%,6%_50%)] print:mt-3">
                        <svg class="h-6 w-6 shrink-0 text-[#512438]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.25 4.75h9.5v14.5L12 16.75l-4.75 2.5zM10 8.25h4M10 11.25h4M10 14.25h2" /></svg>
                        <h1 class="text-lg font-extrabold uppercase tracking-wide print:text-base">
                            Customer Receipt
                            <span class="sr-only">Order Receipt</span>
                        </h1>
                    </div>
                    <p class="mt-2 text-[10px] font-extrabold uppercase tracking-wide text-[#9a6c7b]">{{ $receiptNumber }}</p>
                </header>

                <section class="grid gap-3 border-b border-dashed border-love-pink-200 px-5 pb-4 sm:grid-cols-2 sm:px-7 print:grid-cols-2 print:gap-2 print:px-5 print:pb-3">
                    @foreach ($detailItems as $detail)
                        <article class="grid grid-cols-[2.25rem_minmax(0,1fr)] items-center gap-3 print:grid-cols-[2rem_minmax(0,1fr)] print:gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full print:h-8 print:w-8 {{ $detail['tone'] === 'blue' ? 'bg-love-blue-100 text-[#2f6fa9]' : 'bg-love-pink-100 text-love-pink-500' }}">
                                <svg class="h-5 w-5 print:h-4 print:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $detail['icon'] }}" /></svg>
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-xs font-extrabold text-[#2f3542]">{{ $detail['label'] }}</h2>
                                <p class="mt-0.5 break-words text-xs font-medium leading-5 text-[#512438] print:leading-4">{{ $detail['value'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="px-5 py-4 sm:px-7 print:px-5 print:py-3">
                    <div class="grid grid-cols-[minmax(0,1.7fr)_3rem_6rem_6rem] gap-2 rounded-lg border border-love-pink-200 bg-love-pink-100/70 px-3 py-2 text-[10px] font-extrabold uppercase tracking-wide text-[#2f3542] print:grid-cols-[minmax(0,1.7fr)_2.5rem_5.5rem_5.5rem]">
                        <span>Item</span>
                        <span class="text-center">Qty</span>
                        <span class="text-right">Unit price</span>
                        <span class="text-right">Line total</span>
                    </div>

                    <div class="divide-y divide-dashed divide-love-pink-200">
                        @foreach ($order->items as $item)
                            <article class="grid grid-cols-[minmax(0,1.7fr)_3rem_6rem_6rem] items-center gap-2 py-2.5 text-xs print:grid-cols-[minmax(0,1.7fr)_2.5rem_5.5rem_5.5rem] print:py-2">
                                <div class="flex min-w-0 items-center gap-3">
                                    <img class="h-11 w-11 shrink-0 rounded-lg border border-love-pink-100 bg-love-cream object-cover print:h-9 print:w-9" src="{{ $item->product_image ?: asset('images/lovebyadelogo.png') }}" alt="{{ $item->product_title }}">
                                    <div class="min-w-0">
                                        <h3 class="font-extrabold text-[#2f3542]">{{ $item->product_title }}</h3>
                                        <p class="mt-0.5 text-[10px] font-semibold leading-4 text-[#6b7280]">{{ $item->category }}</p>
                                    </div>
                                </div>
                                <p class="text-center text-sm font-medium text-[#2f3542]">{{ $item->quantity }}</p>
                                <p class="text-right font-bold text-[#2f3542]">&#8369;{{ number_format((float) $item->unit_price, 2) }}</p>
                                <p class="text-right font-extrabold text-[#2f3542]">&#8369;{{ number_format((float) $item->line_total, 2) }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="grid justify-end px-5 pb-5 sm:px-7 print:px-5 print:pb-4">
                    <dl class="grid w-full max-w-xs gap-2 border-t border-love-pink-200 pt-4 print:pt-3">
                        <div class="flex items-center justify-between gap-4 text-xs font-bold text-[#512438]">
                            <dt>Subtotal</dt>
                            <dd>&#8369;{{ number_format((float) $order->subtotal, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-xs font-bold text-[#512438]">
                            <dt>Delivery Fee</dt>
                            <dd>&#8369;{{ number_format((float) $order->delivery_fee, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-xs font-bold text-love-pink-500">
                            <dt>Discount</dt>
                            <dd>-&#8369;{{ number_format((float) $order->discount, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 rounded-lg bg-love-blue-100 px-3 py-2 text-lg font-extrabold text-[#2f3542] print:text-base">
                            <dt>Total Paid</dt>
                            <dd>&#8369;{{ number_format((float) $order->total, 2) }}</dd>
                        </div>
                    </dl>
                </section>

                <footer class="relative border-t border-dashed border-love-pink-200 px-5 pb-5 pt-4 text-center sm:px-7 print:px-5 print:pb-4 print:pt-3">
                    <p class="font-display text-4xl font-bold leading-none text-love-pink-400 print:text-3xl">Thank you</p>
                    <p class="mt-0.5 font-display text-3xl font-bold leading-none text-love-blue-400 print:text-2xl">for your order!</p>
                    <p class="mx-auto mt-3 max-w-md text-xs font-medium leading-5 text-[#512438]">We appreciate your trust in Loveby_Ade. Hope to sweeten your day again soon.</p>
                    <p class="mt-2 text-base text-love-pink-400">&hearts;</p>
                </footer>

                <nav class="grid gap-3 border-t border-love-pink-100 bg-love-cream px-5 py-4 sm:grid-cols-3 sm:px-7 print:hidden" aria-label="Receipt actions">
                    <a class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 bg-white px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $returnUrl }}">
                        {{ $returnLabel }}
                    </a>
                    <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full border border-love-blue-100 bg-love-blue-100/80 px-5 text-sm font-extrabold text-[#23445c] transition hover:-translate-y-0.5 hover:bg-love-blue-200 focus:outline-none focus:ring-4 focus:ring-love-blue-100" type="button" data-print-receipt>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.25 8.75v-4h9.5v4M7.25 17.25h-1.5a2 2 0 0 1-2-2v-4.5a2 2 0 0 1 2-2h12.5a2 2 0 0 1 2 2v4.5a2 2 0 0 1-2 2h-1.5M7.25 14.25h9.5v5.5h-9.5z" /></svg>
                        <span>Print receipt</span>
                    </button>
                    <a class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $downloadUrl }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v10M8.25 11.25 12 15l3.75-3.75M5.75 19.25h12.5" /></svg>
                        <span>Download PDF</span>
                    </a>
                </nav>
            </main>
        </div>
    </div>
@endsection
