@extends('layouts.guest')

@section('title', 'Loveby_Ade | Home')
@section('description', 'Loveby_Ade dessert e-commerce homepage.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <div class="absolute left-[-6rem] top-28 h-72 w-72 rounded-full bg-love-pink-300/70 blur-3xl"></div>
        <div class="absolute right-[-5rem] top-[22rem] h-80 w-80 rounded-full bg-love-blue-300/65 blur-3xl"></div>

        {{-- Store navigation --}}
        <x-home.store-header />

        <main>
            {{-- Hero section --}}
            <section id="home" class="relative overflow-hidden">
                <article class="relative min-h-[82vh] lg:min-h-[calc(100dvh-5.5rem)]">
                    <img
                        class="absolute inset-0 h-full w-full object-cover"
                        src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=2200&q=80"
                        alt="Loveby_Ade dessert hero background"
                    >
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.36)_0%,rgba(15,23,42,0.58)_48%,rgba(15,23,42,0.74)_100%)]"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(244,114,168,0.22),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(56,189,248,0.18),transparent_24%)]"></div>

                    <div class="relative flex min-h-[82vh] items-center justify-center px-6 py-16 text-center sm:px-10 lg:min-h-[calc(100dvh-5.5rem)]">
                        <div class="max-w-4xl">
                            <h1 class="mt-6 font-display text-5xl leading-tight text-white sm:text-6xl xl:text-7xl">
                                Make every moment sweeter with Loveby_Ade.
                            </h1>
                            <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-white/82 sm:text-xl">
                            </p>

                            <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                                <a class="inline-flex items-center justify-center rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-slate-900 shadow-[0_18px_40px_-24px_rgba(255,255,255,0.6)] transition hover:-translate-y-0.5 hover:bg-love-pink-100" href="{{ route('products.index') }}">
                                    Shop Products
                                </a>
                                <a class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-md transition hover:-translate-y-0.5 hover:bg-white/18" href="#products">
                                    View Best Sellers
                                </a>
                            </div>

                            <p class="mt-6 text-sm font-medium text-white/70">
                                Budget-friendly treats from ₱80 to ₱150.
                            </p>
                        </div>
                    </div>
                </article>
            </section>

            <x-home.category-section />

            <x-home.promo-banner :promotions="$activePromotions" />

            {{-- Featured products --}}
            <section id="products" class="py-16">
                <div class="mx-auto max-w-[86rem] px-4 sm:px-6 lg:px-8">
                    <x-home.section-heading
                        title="Trending products"
                        action-label="Shop the collection"
                        :action-href="route('products.index')"
                    />

                    <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        @foreach ($trendingProducts as $product)
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
                    </div>
                </div>
            </section>

            {{-- Recommended section --}}
            <section id="recommended" class="py-16">
                <div class="mx-auto max-w-[86rem] px-4 sm:px-6 lg:px-8">
                    <x-home.section-heading
                        title="Recommended for you"
                        action-label="Shop the collection"
                        :action-href="route('products.index')"
                    />

                    <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        @foreach ($recommendedProducts as $product)
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
                    </div>
                </div>
            </section>

            {{-- About section --}}
            <section id="about" class="py-16">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8">
                    <article class="rounded-[2.5rem] border border-white/75 bg-white/90 p-8 shadow-[0_28px_60px_-34px_rgba(15,23,42,0.28)]">
                        <span class="inline-flex rounded-full border border-love-blue-200 bg-love-blue-100/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-blue-500">
                            About Loveby_Ade
                        </span>
                        <h2 class="mt-5 font-display text-4xl text-slate-900 sm:text-5xl">Dessert shopping made soft, modern, and easy to browse.</h2>
                        <p class="mt-4 text-sm leading-8 text-slate-600 sm:text-base">
                            Loveby_Ade is a dessert-themed e-commerce experience focused on affordable, attractive sweet treats. The storefront is designed for quick discovery, clear pricing, and a cozy premium bakery mood.
                        </p>
                    </article>
                    <img class="h-full min-h-[320px] w-full rounded-[2.5rem] border border-white/75 object-cover shadow-[0_28px_60px_-34px_rgba(15,23,42,0.28)]" src="https://images.unsplash.com/photo-1550617931-e17a7b70dce2?auto=format&fit=crop&w=1200&q=80" alt="Dessert display" loading="lazy">
                </div>
            </section>

            {{-- Testimonials --}}
            <section class="py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <x-home.section-heading
                        eyebrow="Testimonials"
                        title="What customers love about the Loveby_Ade dessert experience."
                        description="Early impressions from customers who care about presentation, value, and a soft premium feel."
                    />

                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        <x-home.testimonial-card
                            name="Alyssa P."
                            image="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&q=80"
                            image-alt="Alyssa P. profile photo"
                            rating="5.0"
                            quote="The prices are affordable, the layout feels premium, and the dessert picks look gift-ready even before checkout."
                        />
                        <x-home.testimonial-card
                            name="Marco D."
                            image="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&q=80"
                            image-alt="Marco D. profile photo"
                            rating="4.8"
                            quote="I like how easy it is to scan the products, compare prices, and jump straight to what is trending."
                        />
                        <x-home.testimonial-card
                            name="Janelle R."
                            image="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=240&q=80"
                            image-alt="Janelle R. profile photo"
                            rating="5.0"
                            quote="The soft colors, promo section, and product cards make the whole shop feel polished and easy to trust."
                        />
                    </div>
                </div>
            </section>
        </main>

        {{-- Store footer --}}
        <x-home.store-footer />
    </div>
@endsection
