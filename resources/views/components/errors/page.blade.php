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

    <div class="relative mx-auto flex min-h-[calc(100dvh-3rem)] max-w-5xl flex-col">
        <div class="flex items-start justify-between gap-4">
            <x-brand-mark :href="route('home')" />
            <a class="inline-flex min-h-11 items-center justify-center rounded-full border border-white/80 bg-white/92 px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-[0_16px_38px_-26px_rgba(15,23,42,0.28)] transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ route('home') }}">
                Browse home
            </a>
        </div>

        <section class="mt-8 flex flex-1 items-center justify-center">
            <div class="w-full max-w-3xl rounded-[2rem] border border-white/80 bg-white/92 p-7 shadow-[0_30px_70px_-42px_rgba(15,23,42,0.28)] sm:p-10">
                <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.3em] text-love-pink-500">{{ $eyebrow }}</p>
                        <div class="mt-5 flex items-end gap-4">
                            <span class="font-display text-6xl leading-none text-slate-950 sm:text-7xl">{{ $code }}</span>
                            <span class="mb-1 inline-flex rounded-full border border-love-blue-200 bg-love-blue-100/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-love-blue-500">
                                Loveby_Ade
                            </span>
                        </div>
                    </div>

                    <div class="error-illustration-motion flex h-32 w-32 shrink-0 items-center justify-center rounded-[2rem] border border-love-blue-100 bg-[linear-gradient(180deg,#ffffff_0%,#fdf2f8_100%)] shadow-[0_18px_42px_-28px_rgba(15,23,42,0.22)] sm:h-40 sm:w-40" data-error-illustration>
                        <img class="h-24 w-24 object-contain sm:h-30 sm:w-30" src="{{ $image }}" alt="{{ $imageAlt }}">
                    </div>
                </div>

                <h1 class="mt-7 max-w-xl font-display text-4xl leading-tight text-slate-950 sm:text-5xl">{{ $title }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-slate-600">{{ $message }}</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a class="inline-flex min-h-12 items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_-24px_rgba(15,23,42,0.78)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ $primaryHref }}">
                        {{ $primaryLabel }}
                    </a>
                    <a class="inline-flex min-h-12 items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" href="{{ $secondaryHref }}">
                        {{ $secondaryLabel }}
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>
