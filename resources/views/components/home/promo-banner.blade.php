@props([
    'promotions' => collect(),
])

@php
    $promotions = collect($promotions);

    if ($promotions->isEmpty()) {
        $promotions = collect([(object) [
            'kind' => \App\Models\Promotion::KIND_DISCOUNT,
            'code' => 'SWEET20',
            'announcement_title' => 'Get 20% off during our one-time dessert sale.',
            'announcement_body' => 'Most of our sweet boxes are prepared in small batches and do not stay in stock for long. Grab your favorite cakes, pastries, and cookies while the sale lasts.',
            'announcement_cta' => 'Claim Offer',
            'image_path' => null,
        ]]);
    }
@endphp

<section id="promo" class="relative overflow-hidden" data-promo-carousel>
    <div class="flex h-[22rem] max-h-[calc(100vh-7rem)] snap-x snap-mandatory overflow-x-auto scroll-smooth [scrollbar-width:none] sm:h-[30rem] lg:h-[38rem] xl:h-[42rem] [&::-webkit-scrollbar]:hidden" aria-label="Announcement board" data-promo-track>
        @foreach ($promotions as $promotion)
            @php
                $isAd = $promotion->kind === \App\Models\Promotion::KIND_AD && filled($promotion->image_path);
                $promoCode = $promotion->code ?? 'SWEET20';
                $promoTitle = $promotion->announcement_title ?: 'Get 20% off during our one-time dessert sale.';
                $promoBody = $promotion->announcement_body ?: 'Most of our sweet boxes are prepared in small batches and do not stay in stock for long. Grab your favorite cakes, pastries, and cookies while the sale lasts.';
                $promoCta = $promotion->announcement_cta ?: 'Claim Offer';
            @endphp

            <article class="relative h-full w-full shrink-0 snap-start overflow-hidden" data-promo-slide>
                @if ($isAd)
                    <div class="absolute inset-0 bg-[#fff8fb]">
                        <img
                            class="absolute inset-0 h-full w-full scale-105 object-cover opacity-30 blur-xl"
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($promotion->image_path) }}"
                            alt=""
                            aria-hidden="true"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(255,248,251,0.84)_0%,rgba(255,255,255,0.36)_24%,rgba(255,255,255,0.36)_76%,rgba(239,248,255,0.84)_100%)]"></div>
                        <img
                            class="relative mx-auto h-full w-full max-w-[86rem] object-contain drop-shadow-[0_28px_70px_rgba(81,36,56,0.20)]"
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($promotion->image_path) }}"
                            alt="Promotion ad"
                            loading="lazy"
                        >
                    </div>
                @else
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,252,253,0.84)_0%,rgba(255,248,251,0.74)_48%,rgba(243,250,255,0.82)_100%)]"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(244,114,168,0.16),transparent_24%),radial-gradient(circle_at_bottom_right,rgba(56,189,248,0.18),transparent_22%)]"></div>

                    <div class="absolute inset-0 md:hidden">
                        <img
                            class="h-full w-full object-cover opacity-35"
                            src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?auto=format&fit=crop&w=1400&q=80"
                            alt="Dessert sale background"
                            loading="lazy"
                        >
                    </div>

                    <div class="absolute inset-0 hidden md:block">
                        <div class="absolute left-0 top-0 h-64 w-52 overflow-hidden rounded-[2rem] opacity-45 shadow-[0_26px_60px_-34px_rgba(15,23,42,0.35)] lg:h-80 lg:w-72">
                            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=1200&q=80" alt="Dessert gift box" loading="lazy">
                        </div>
                        <div class="absolute left-[21%] top-8 h-64 w-72 overflow-hidden rounded-[2rem] opacity-50 shadow-[0_26px_60px_-34px_rgba(15,23,42,0.35)] lg:h-84 lg:w-[28rem]">
                            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?auto=format&fit=crop&w=1400&q=80" alt="Brownie and dessert assortment" loading="lazy">
                        </div>
                        <div class="absolute right-[17%] top-0 h-60 w-72 overflow-hidden rounded-[2rem] opacity-46 shadow-[0_26px_60px_-34px_rgba(15,23,42,0.35)] lg:h-80 lg:w-[25rem]">
                            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1400&q=80" alt="Pastries and bakery treats" loading="lazy">
                        </div>
                        <div class="absolute bottom-0 left-0 h-52 w-56 overflow-hidden rounded-[2rem] opacity-44 shadow-[0_26px_60px_-34px_rgba(15,23,42,0.35)] lg:h-64 lg:w-80">
                            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=1200&q=80" alt="Mini cakes and sweet cups" loading="lazy">
                        </div>
                        <div class="absolute bottom-0 right-0 h-60 w-56 overflow-hidden rounded-[2rem] opacity-46 shadow-[0_26px_60px_-34px_rgba(15,23,42,0.35)] lg:h-80 lg:w-80">
                            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=1200&q=80" alt="Fresh cookie batch" loading="lazy">
                        </div>
                    </div>

                    <div class="relative flex h-full items-center justify-center px-6 py-10 text-center sm:px-10 lg:py-16">
                        <div class="max-w-5xl">
                            <span class="inline-flex rounded-full border border-love-pink-200/80 bg-white/88 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-pink-500 shadow-[0_12px_30px_-22px_rgba(15,23,42,0.35)]">
                                {{ $promoCode }}
                            </span>

                            <h2 class="mt-6 font-display text-4xl leading-tight text-slate-900 sm:text-5xl lg:text-6xl xl:text-7xl">
                                {{ $promoTitle }}
                            </h2>

                            <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-slate-700 sm:text-xl">
                                {{ $promoBody }}
                            </p>

                            <a class="mt-8 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-8 py-4 text-sm font-semibold text-white shadow-[0_20px_48px_-28px_rgba(15,23,42,0.72)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="#products">
                                {{ $promoCta }}
                            </a>
                        </div>
                    </div>
                @endif
            </article>
        @endforeach
    </div>

    @if ($promotions->count() > 1)
        <button class="absolute left-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-white/86 text-slate-700 shadow-[0_18px_42px_-30px_rgba(15,23,42,0.55)] transition hover:-translate-y-[55%] hover:bg-white focus:outline-none focus:ring-4 focus:ring-white/70 sm:flex" type="button" aria-label="Previous announcement" data-promo-previous>
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m15 5.75-6.25 6.25L15 18.25" />
            </svg>
        </button>

        <button class="absolute right-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-white/86 text-slate-700 shadow-[0_18px_42px_-30px_rgba(15,23,42,0.55)] transition hover:-translate-y-[55%] hover:bg-white focus:outline-none focus:ring-4 focus:ring-white/70 sm:flex" type="button" aria-label="Next announcement" data-promo-next>
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 5.75 6.25 6.25L9 18.25" />
            </svg>
        </button>

        <div class="absolute inset-x-0 bottom-5 flex justify-center gap-2">
            @foreach ($promotions as $promotionIndex => $promotion)
                <button class="h-2 w-8 rounded-full bg-white/70 shadow-[0_8px_20px_-12px_rgba(15,23,42,0.55)] transition data-[active=true]:bg-love-pink-400" type="button" aria-label="Show announcement {{ $promotionIndex + 1 }}" data-promo-dot data-promo-index="{{ $promotionIndex }}" data-active="{{ $loop->first ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
    @endif
</section>
