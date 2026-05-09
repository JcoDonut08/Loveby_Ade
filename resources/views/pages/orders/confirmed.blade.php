@extends('layouts.guest')

@section('title', 'Order Confirmed | Loveby_Ade')
@section('description', 'Loveby_Ade checkout confirmation with order tracking number, dessert items, and total.')
@section('body_classes', 'bg-white text-slate-950')

@section('content')
    <div class="min-h-screen">
        <x-home.store-header />

        <main class="grid min-h-[calc(100dvh-5.35rem)] lg:grid-cols-[0.92fr_1fr]">
            <section class="relative min-h-[22rem] overflow-hidden bg-slate-100 lg:min-h-full">
                <img
                    class="absolute inset-0 h-full w-full object-cover"
                    src="https://images.unsplash.com/photo-1550617931-e17a7b70dce2?auto=format&fit=crop&w=1400&q=80"
                    alt="Loveby_Ade dessert shelves"
                >
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0)_0%,rgba(255,231,241,0.12)_100%)]"></div>
            </section>

            <section class="flex items-center px-5 py-12 sm:px-10 lg:px-16">
                <article class="mx-auto w-full max-w-2xl">
                    <p class="text-sm font-semibold text-love-blue-500">Payment successful</p>
                    <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-normal text-slate-950 sm:text-5xl">
                        Thanks for ordering
                    </h1>
                    <p class="mt-3 max-w-xl text-base leading-7 text-slate-500">
                        We appreciate your order. We're currently preparing your desserts and will send your confirmation very soon.
                    </p>

                    <div class="mt-10">
                        <p class="text-sm font-extrabold text-slate-950">Tracking number</p>
                        <p class="mt-2 text-sm font-semibold text-love-blue-500">LBA-51547878755545848512</p>
                    </div>

                    <div class="mt-7 divide-y divide-slate-200 border-y border-slate-200">
                        <article class="grid grid-cols-[6rem_minmax(0,1fr)_auto] gap-5 py-6">
                            <img class="aspect-square w-24 rounded-md bg-slate-100 object-cover" src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80" alt="Pastel Donut Box">
                            <div class="min-w-0">
                                <h2 class="text-sm font-extrabold text-slate-950">Pastel Donut Box</h2>
                                <p class="mt-1 text-sm font-medium text-slate-500">Assorted pastel donuts</p>
                                <p class="mt-2 text-sm font-medium text-slate-500">Qty 2</p>
                            </div>
                            <p class="text-sm font-extrabold text-slate-950">&#8369;240.00</p>
                        </article>

                        <article class="grid grid-cols-[6rem_minmax(0,1fr)_auto] gap-5 py-6">
                            <img class="aspect-square w-24 rounded-md bg-slate-100 object-cover" src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=300&q=80" alt="Chocolate Chip Cookies">
                            <div class="min-w-0">
                                <h2 class="text-sm font-extrabold text-slate-950">Chocolate Chip Cookies</h2>
                                <p class="mt-1 text-sm font-medium text-slate-500">Classic cookie pack</p>
                                <p class="mt-2 text-sm font-medium text-slate-500">Qty 1</p>
                            </div>
                            <p class="text-sm font-extrabold text-slate-950">&#8369;90.00</p>
                        </article>

                        <article class="grid grid-cols-[6rem_minmax(0,1fr)_auto] gap-5 py-6">
                            <img class="aspect-square w-24 rounded-md bg-slate-100 object-cover" src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=300&q=80" alt="Mini Cake Cups">
                            <div class="min-w-0">
                                <h2 class="text-sm font-extrabold text-slate-950">Mini Cake Cups</h2>
                                <p class="mt-1 text-sm font-medium text-slate-500">Box of mini cake cups</p>
                                <p class="mt-2 text-sm font-medium text-slate-500">Qty 1</p>
                            </div>
                            <p class="text-sm font-extrabold text-slate-950">&#8369;150.00</p>
                        </article>
                    </div>

                    <dl class="mt-6 space-y-5">
                        <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                            <dt>Subtotal</dt>
                            <dd class="font-extrabold text-slate-950">&#8369;480.00</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                            <dt>Shipping</dt>
                            <dd class="font-extrabold text-slate-950">&#8369;0.00</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-sm font-medium text-slate-500">
                            <dt>Promo discount</dt>
                            <dd class="font-extrabold text-slate-950">&#8369;0.00</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-slate-200 pt-6 text-base font-extrabold text-slate-950">
                            <dt>Total</dt>
                            <dd>&#8369;480.00</dd>
                        </div>
                    </dl>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('products.index') }}">
                            Continue shopping
                        </a>
                        <a class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ route('notifications') }}">
                            View updates
                        </a>
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
