@extends('layouts.guest')

@section('title', 'Favorites | Loveby_Ade')
@section('description', 'Saved Loveby_Ade desserts with prices, ratings, and quick add to cart actions.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <x-home.store-header />

        <main class="mx-auto max-w-[86rem] px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[1.5rem] border border-white/80 bg-white/90 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.26em] text-love-pink-500">Saved desserts</p>
                        <h1 class="mt-2 font-display text-4xl text-slate-950 sm:text-5xl">Favorites</h1>
                        <p class="mt-2 text-sm font-medium text-slate-500">Your favorite Loveby_Ade treats, ready to add to cart anytime.</p>
                    </div>

                    <span class="inline-flex w-max items-center rounded-full bg-love-pink-100 px-4 py-2 text-sm font-extrabold text-love-pink-500" data-favorites-count>
                        3 saved items
                    </span>
                </div>
            </section>

            <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-favorites-grid>
                <x-store.favorite-card image="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80" title="Pastel Donut Box" price="120" rating="4.8" />
                <x-store.favorite-card image="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=900&q=80" title="Mini Cake Cups" price="150" rating="4.9" />
                <x-store.favorite-card image="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=900&q=80" title="Chocolate Chip Cookies" price="90" rating="4.7" />
            </section>

            <x-store.empty-state class="mt-8 hidden" title="No favorites yet" description="Tap a heart on any dessert and your saved treats will appear here." icon="heart" action-label="Browse desserts" :action-href="route('home').'#products'" data-favorites-empty />
        </main>

        <x-home.store-footer />
    </div>
@endsection
