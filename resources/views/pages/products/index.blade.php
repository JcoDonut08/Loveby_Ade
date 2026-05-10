@extends('layouts.guest')

@section('title', 'Products | Loveby_Ade')
@section('description', 'Browse Loveby_Ade desserts with search, category, and price filters.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@php
    $selectedCategory = $filters['category'] ?? '';
    $search = $filters['search'] ?? '';
    $minimumPrice = $filters['min_price'] ?? '';
    $maximumPrice = $filters['max_price'] ?? '';
@endphp

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <x-home.store-header />

        <main class="mx-auto max-w-[86rem] px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[1.5rem] border border-white/80 bg-white/92 p-5 shadow-[0_24px_58px_-40px_rgba(15,23,42,0.28)]">
                <form class="grid gap-4 lg:grid-cols-[1.35fr_0.95fr_0.75fr_0.75fr_auto] lg:items-end" action="{{ route('products.index') }}" method="GET" data-auto-filter-form>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500" for="product-search">Search</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6.5" />
                                    <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                                </svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-white px-12 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="product-search" name="search" type="search" value="{{ $search }}" placeholder="Search cakes, donuts, cookies...">
                        </div>
                        @error('search')
                            <p class="mt-2 text-xs font-semibold text-love-pink-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500" for="product-category">Category</label>
                        <select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="product-category" name="category">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-2 text-xs font-semibold text-love-pink-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500" for="minimum-price">Min price</label>
                        <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="minimum-price" name="min_price" type="number" min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}" value="{{ $minimumPrice }}" placeholder="{{ $priceRange['min'] }}">
                        @error('min_price')
                            <p class="mt-2 text-xs font-semibold text-love-pink-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500" for="maximum-price">Max price</label>
                        <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="maximum-price" name="max_price" type="number" min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}" value="{{ $maximumPrice }}" placeholder="{{ $priceRange['max'] }}">
                        @error('max_price')
                            <p class="mt-2 text-xs font-semibold text-love-pink-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-3">
                        <a class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ route('products.index') }}">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="mt-5 flex flex-wrap gap-2">
                    <a class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-bold transition {{ $selectedCategory === '' ? 'border-love-pink-300 bg-love-pink-100 text-love-pink-500' : 'border-slate-200 bg-white text-slate-500 hover:border-love-pink-200 hover:text-love-pink-500' }}" href="{{ route('products.index', array_filter(['search' => $search, 'min_price' => $minimumPrice, 'max_price' => $maximumPrice])) }}">
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-bold transition {{ $selectedCategory === $category ? 'border-love-pink-300 bg-love-pink-100 text-love-pink-500' : 'border-slate-200 bg-white text-slate-500 hover:border-love-pink-200 hover:text-love-pink-500' }}" href="{{ route('products.index', array_filter(['search' => $search, 'category' => $category, 'min_price' => $minimumPrice, 'max_price' => $maximumPrice])) }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </section>

            @if ($products->isNotEmpty())
                <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    @foreach ($products as $product)
                        <x-home.product-card
                            :image="$product['image']"
                            :title="$product['title']"
                            :price="$product['price']"
                            :sold="$product['sold_label']"
                            :stock-left="$product['stock_label']"
                            :rating="$product['rating']"
                            :href="$product['show_url']"
                            :slug="$product['slug']"
                        />
                    @endforeach
                </section>
            @else
                <x-store.empty-state class="mt-8" title="No products found" description="Try a different search, category, or price range to browse more desserts." icon="sparkle" action-label="Clear filters" :action-href="route('products.index')" />
            @endif
        </main>

        <x-home.store-footer />
    </div>
@endsection
