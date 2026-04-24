@extends('layouts.guest')

@section('title', 'Loveby_Ade | Home')
@section('description', 'Loveby_Ade homepage placeholder.')
@section('body_classes', 'h-[100dvh] overflow-hidden bg-[linear-gradient(180deg,#fff9fb_0%,#eff9ff_52%,#fffaf6_100%)] text-slate-900')

@section('content')
    <main class="flex h-[100dvh] items-center justify-center px-4">
        <section class="w-full max-w-xl rounded-[2rem] border border-white/70 bg-white/88 p-8 text-center shadow-[0_36px_90px_-42px_rgba(15,23,42,0.35)] backdrop-blur-xl">
            <div class="flex justify-center">
                <x-brand-mark :href="route('home')" />
            </div>

            <h1 class="mt-6 font-display text-4xl text-slate-900">Loveby_Ade</h1>
            <p class="mt-3 text-sm leading-7 text-slate-500">
                Homepage placeholder for your dessert e-commerce project.
            </p>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-love-pink-500" href="{{ route('login') }}">
                    Go to Login
                </a>
                <a class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-love-blue-200 hover:text-love-blue-500" href="{{ route('register') }}">
                    Go to Register
                </a>
            </div>
        </section>
    </main>
@endsection
