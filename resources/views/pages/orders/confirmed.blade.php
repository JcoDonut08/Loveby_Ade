@extends('layouts.guest')

@section('title', 'Order Confirmed | Loveby_Ade')
@section('description', 'Loveby_Ade checkout confirmation with order tracking number, dessert items, and total.')
@section('body_classes', 'bg-white text-slate-950')

@section('content')
    <div class="min-h-screen">
        <x-home.store-header />

        <main class="grid min-h-[calc(100dvh-5.35rem)] lg:grid-cols-[0.92fr_1fr]">
            <section class="relative flex min-h-[22rem] items-center justify-center overflow-hidden bg-[linear-gradient(180deg,#fff9fb_0%,#eefaff_100%)] px-8 py-10 lg:min-h-full">
                <div class="absolute left-8 top-10 h-28 w-28 rounded-full bg-love-pink-100/80 blur-3xl"></div>
                <div class="absolute bottom-12 right-8 h-32 w-32 rounded-full bg-love-blue-100/80 blur-3xl"></div>
                <img
                    class="relative max-h-[32rem] w-full max-w-[36rem] object-contain drop-shadow-[0_34px_70px_rgba(81,36,56,0.16)] lg:max-h-[46rem] lg:max-w-[42rem]"
                    src="{{ asset('images/thank-you-ordering.png') }}"
                    alt="Cute cupcake holding a thanks for ordering sign"
                >
            </section>

            <section class="flex items-center px-5 py-12 sm:px-10 lg:px-16">
                <article class="mx-auto w-full max-w-2xl">
                    <p class="text-sm font-semibold text-love-blue-500">Payment successful</p>
                    <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-normal text-slate-950 sm:text-5xl">
                        Thanks for ordering
                    </h1>
                    <p class="mt-3 max-w-xl text-base leading-7 text-slate-500">
                        We appreciate your order. Your desserts are now pending admin review before preparation and delivery updates begin.
                    </p>

                    @if ($order)
                        <div class="mt-10">
                            <p class="text-sm font-extrabold text-slate-950">Tracking number</p>
                            <p class="mt-2 text-sm font-semibold text-love-blue-500">{{ $order->order_number }}</p>
                        </div>

                        <div class="mt-7 divide-y divide-slate-200 border-y border-slate-200">
                            @foreach ($order->items as $item)
                                <article class="grid grid-cols-[6rem_minmax(0,1fr)_auto] gap-5 py-6">
                                    <img class="aspect-square w-24 rounded-md bg-slate-100 object-cover" src="{{ $item->product_image }}" alt="{{ $item->product_title }}">
                                    <div class="min-w-0">
                                        <h2 class="text-sm font-extrabold text-slate-950">{{ $item->product_title }}</h2>
                                        <p class="mt-1 text-sm font-medium text-slate-500">{{ $item->category }}</p>
                                        <p class="mt-2 text-sm font-medium text-slate-500">Qty {{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-sm font-extrabold text-slate-950">&#8369;{{ number_format((float) $item->line_total, 2) }}</p>
                                </article>
                            @endforeach
                        </div>

                        <dl class="mt-6 space-y-5">
                            <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                                <dt>Subtotal</dt>
                                <dd class="font-extrabold text-slate-950">&#8369;{{ number_format((float) $order->subtotal, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                                <dt>Shipping</dt>
                                <dd class="font-extrabold text-slate-950">
                                    @if ((float) $order->delivery_fee === 0.0)
                                        Free
                                    @else
                                        &#8369;{{ number_format((float) $order->delivery_fee, 2) }}
                                    @endif
                                </dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                                <dt>Promo discount</dt>
                                <dd class="font-extrabold text-slate-950">&#8369;{{ number_format((float) $order->discount, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-t border-slate-200 pt-6 text-base font-extrabold text-slate-950">
                                <dt>Total</dt>
                                <dd>&#8369;{{ number_format((float) $order->total, 2) }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="mt-10 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-600">
                            Your latest order details will appear here right after checkout.
                        </div>
                    @endif

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('products.index') }}">
                            Continue shopping
                        </a>
                        @if ($order)
                            <a class="inline-flex items-center justify-center rounded-full border border-love-pink-200 bg-love-pink-100/70 px-6 py-3 text-sm font-semibold text-love-pink-500 transition hover:-translate-y-0.5 hover:bg-love-pink-200" href="{{ route('orders.receipt', $order) }}">
                                View invoice
                            </a>
                        @endif
                        <a class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ route('orders.index') }}">
                            View updates
                        </a>
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
