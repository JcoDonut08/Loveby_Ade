@extends('layouts.guest')

@section('title', 'Notifications | Loveby_Ade')
@section('description', 'Customer notifications for Loveby_Ade orders, payments, promos, and deliveries.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@php
    $notifications = $notifications ?? collect();
@endphp

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <x-home.store-header />

        <main class="mx-auto max-w-[86rem] px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[1.5rem] border border-white/80 bg-white/90 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.26em] text-love-pink-500">Customer center</p>
                        <h1 class="mt-2 font-display text-4xl text-slate-950 sm:text-5xl">Notifications</h1>
                        <p class="mt-2 text-sm font-medium text-slate-500">Order, payment, delivery, and promo updates in one calm place.</p>
                    </div>

                    @auth
                        @if ($notifications->isNotEmpty())
                            <form method="POST" action="{{ route('notifications.read') }}">
                                @csrf
                                <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500" type="submit">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />
                                    </svg>
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </section>

            @if (session('status'))
                <div class="mt-5 rounded-[1.25rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-extrabold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <section class="mt-5 grid gap-3" data-customer-notifications>
                @foreach ($notifications as $notification)
                    <x-store.notification-row
                        :title="$notification['title']"
                        :message="$notification['message']"
                        :time="$notification['time']"
                        :icon="$notification['icon']"
                        :tone="$notification['tone']"
                        :unread="$notification['unread']"
                        :read-action="$notification['unread'] ? route('notifications.read-one', $notification['id']) : null"
                    />
                @endforeach
            </section>

            @if ($notifications->isEmpty())
                <x-store.empty-state class="mt-5" title="No notifications yet" description="When your orders and delivery updates arrive, they will show up here." icon="bell" action-label="Continue shopping" :action-href="route('home').'#products'" data-notifications-empty />
            @endif
        </main>

        <x-home.store-footer />
    </div>
@endsection
