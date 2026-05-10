@extends('layouts.guest')

@section('title', 'Account | Loveby_Ade')
@section('description', 'View your Loveby_Ade account details.')
@section('body_classes', 'bg-[linear-gradient(180deg,#fff3f8_0%,#eff8ff_52%,#fff8f3_100%)] text-slate-900')

@section('content')
    <x-home.store-header />

    <main class="mx-auto min-h-[calc(100dvh-5.35rem)] max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-[2rem] border border-white/80 bg-white/92 p-6 shadow-[0_28px_70px_-42px_rgba(15,23,42,0.34)] sm:p-8">
            <span class="text-xs font-extrabold uppercase tracking-[0.28em] text-love-pink-500">Account</span>
            <h1 class="mt-3 font-display text-4xl text-slate-950">Your profile</h1>
            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Name</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</dd>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Email</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800">{{ auth()->user()->email }}</dd>
                </div>
            </dl>
        </section>
    </main>

    <x-home.store-footer />
@endsection
