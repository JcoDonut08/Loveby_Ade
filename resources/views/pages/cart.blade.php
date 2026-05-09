@extends('layouts.guest')

@section('title', 'Cart | Loveby_Ade')
@section('description', 'Review Loveby_Ade cart items, quantities, promo code, subtotal, and checkout action.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@section('content')
    <div class="relative min-h-screen overflow-x-hidden" data-cart-page>
        <x-home.store-header />

        <main class="mx-auto max-w-[86rem] px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[1.5rem] border border-white/80 bg-white/90 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                <p class="text-sm font-bold uppercase tracking-[0.26em] text-love-pink-500">Checkout preview</p>
                <h1 class="mt-2 font-display text-4xl text-slate-950 sm:text-5xl">Shopping Cart</h1>
                <p class="mt-2 text-sm font-medium text-slate-500">Adjust quantities, apply a promo code, and review your dessert subtotal.</p>
            </section>

            <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_26rem]" data-cart-content>
                <section class="rounded-[1.5rem] border border-white/80 bg-white/92 p-5 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                    <div class="flex items-center justify-between gap-4 border-b border-love-pink-100/80 pb-4">
                        <h2 class="text-2xl font-extrabold text-slate-950">Cart items</h2>
                        <span class="rounded-full bg-love-blue-100 px-4 py-2 text-sm font-extrabold text-love-blue-500" data-cart-item-count>4 items</span>
                    </div>

                    <x-store.cart-item image="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80" title="Pastel Donut Box" price="120" quantity="2" note="Assorted pastel donuts" />
                    <x-store.cart-item image="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=600&q=80" title="Chocolate Chip Cookies" price="90" quantity="1" note="Classic cookie pack" />
                    <x-store.cart-item image="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80" title="Mini Cake Cups" price="150" quantity="1" note="Box of mini cake cups" />
                </section>

                <aside class="h-max rounded-[1.5rem] border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]" data-cart-summary>
                    <h2 class="text-2xl font-extrabold text-slate-950">Order summary</h2>

                    <label class="mt-6 block text-sm font-extrabold text-slate-700" for="promo-code">Promo code</label>
                    <div class="mt-2 flex gap-2">
                        <input class="min-w-0 flex-1 rounded-full border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="promo-code" type="text" placeholder="Enter discount code if any">
                        <button class="rounded-full bg-love-pink-100 px-5 text-sm font-extrabold text-love-pink-500 transition hover:bg-love-pink-200" type="button">Apply</button>
                    </div>

                    <div class="mt-6 space-y-4 border-y border-love-pink-100/80 py-5">
                        <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                            <span>Subtotal</span>
                            <span class="text-base font-extrabold text-slate-950" data-cart-subtotal>&#8369;480.00</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                            <span>Promo discount</span>
                            <span class="text-base font-extrabold text-slate-950">&#8369;0.00</span>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-4">
                        <span class="text-lg font-extrabold text-slate-950">Order total</span>
                        <span class="text-2xl font-extrabold text-love-pink-500" data-cart-total>&#8369;480.00</span>
                    </div>

                    <a class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('orders.confirmed') }}">
                        Checkout
                    </a>
                </aside>
            </div>

            <x-store.empty-state class="mt-8 hidden" title="Your cart is empty" description="Add desserts to your cart and they will appear here with quantity controls and subtotal details." icon="cart" action-label="Shop desserts" :action-href="route('home').'#products'" data-cart-empty />
        </main>

        <x-home.store-footer />
    </div>
@endsection
