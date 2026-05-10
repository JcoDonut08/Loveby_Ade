@extends('layouts.guest')

@section('title', 'Orders | Loveby_Ade')
@section('description', 'View your Loveby_Ade orders.')
@section('body_classes', 'bg-[linear-gradient(180deg,#fff3f8_0%,#eff8ff_52%,#fff8f3_100%)] text-slate-900')

@section('content')
    <x-home.store-header />

    <main class="mx-auto min-h-[calc(100dvh-5.35rem)] max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-[2rem] border border-white/80 bg-white/92 p-6 shadow-[0_28px_70px_-42px_rgba(15,23,42,0.34)] sm:p-8">
            <span class="text-xs font-extrabold uppercase tracking-[0.28em] text-love-pink-500">Orders</span>
            <h1 class="mt-3 font-display text-4xl text-slate-950">Your orders</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">You do not have any orders yet.</p>
        </section>
    </main>

    <x-home.store-footer />
@endsection
