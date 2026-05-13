@props([
    'code',
    'title',
    'message',
    'image',
    'imageAlt',
    'eyebrow' => 'Something went wrong',
    'primaryHref' => route('home'),
    'primaryLabel' => 'Back to homepage',
    'secondaryHref' => route('contact'),
    'secondaryLabel' => 'Contact admin',
])

<main class="relative overflow-hidden px-4 py-6 sm:px-6 lg:px-8">
    <div class="absolute inset-x-0 top-0 h-48 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.65),transparent_68%)]"></div>
    <div class="absolute left-[-3rem] top-14 h-52 w-52 rounded-full bg-love-pink-300/70 blur-3xl"></div>
    <div class="absolute bottom-8 right-[-2.5rem] h-52 w-52 rounded-full bg-love-blue-300/70 blur-3xl"></div>

    <div class="relative mx-auto flex min-h-[calc(100dvh-3rem)] max-w-7xl flex-col">
        <div class="flex items-start">
            <x-brand-mark :href="route('home')" />
        </div>

        <section class="mt-8 flex flex-1 items-center justify-center">
            <div class="relative w-full overflow-hidden rounded-[2rem] border border-white/80 bg-white/92 p-7 shadow-[0_30px_70px_-42px_rgba(15,23,42,0.28)] sm:p-10 lg:p-14">
                <div class="pointer-events-none absolute -bottom-16 -left-16 h-44 w-44 rounded-full border-2 border-dashed border-love-pink-200/70"></div>
                <div class="pointer-events-none absolute -right-12 -top-12 h-36 w-36 rounded-full border-2 border-dashed border-love-pink-200/70"></div>

                <div class="relative grid gap-10 lg:grid-cols-[minmax(0,0.72fr)_minmax(18rem,1fr)] lg:items-center">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.3em] text-love-pink-500">{{ $eyebrow }}</p>
                        <span class="mt-6 block font-display text-7xl leading-none text-slate-950 sm:text-8xl">{{ $code }}</span>
                        <span class="mt-5 inline-flex rounded-full border border-love-blue-200 bg-love-blue-100/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-blue-500">
                            Loveby_Ade
                        </span>

                        <h1 class="mt-8 max-w-xl font-display text-4xl leading-tight text-slate-950 sm:text-5xl">{{ $title }}</h1>
                        <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">{{ $message }}</p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a class="inline-flex min-h-12 items-center justify-center gap-3 rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_-24px_rgba(15,23,42,0.78)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ $primaryHref }}">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-love-pink-100 text-love-pink-500" aria-hidden="true">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M13 5l7 7-7 7" />
                                    </svg>
                                </span>
                                {{ $primaryLabel }}
                            </a>
                            <a class="inline-flex min-h-12 items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ $secondaryHref }}">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-love-pink-100 text-love-pink-500" aria-hidden="true">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 11 9-7 9 7" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v10h14V10" />
                                    </svg>
                                </span>
                                {{ $secondaryLabel }}
                            </a>
                        </div>
                    </div>

                    <div class="error-illustration-motion flex min-h-[18rem] items-center justify-center" data-error-illustration>
                        <img class="max-h-[26rem] w-full max-w-[34rem] object-contain lg:max-h-[34rem] lg:max-w-none" src="{{ $image }}" alt="{{ $imageAlt }}">
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
