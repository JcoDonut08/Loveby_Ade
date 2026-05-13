@props(['steps'])

<div class="relative overflow-hidden rounded-2xl border border-white/70 bg-white/45 p-4 shadow-[0_18px_54px_-42px_rgba(15,23,42,0.35)]">
    <ol class="relative grid gap-3 md:grid-cols-4" aria-label="Checkout progress">
        @foreach ($steps as $number => $label)
            <li
                class="relative rounded-xl border border-white/80 bg-white/88 p-3 shadow-[0_16px_40px_-34px_rgba(15,23,42,0.28)] transition duration-500 ease-out"
                data-checkout-progress-step="{{ $number }}"
            >
                <div class="flex items-center gap-3">
                    <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full border text-sm font-extrabold transition duration-500 ease-out" data-checkout-progress-circle>
                        <span class="absolute inset-0 hidden rounded-full bg-love-pink-300/40 motion-safe:animate-ping" data-checkout-progress-ping></span>
                        <span data-checkout-progress-number>{{ $number }}</span>
                        <svg class="hidden h-5 w-5 scale-50 opacity-0 transition duration-500 ease-out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" data-checkout-progress-check>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5 9.5 17 19 7" />
                        </svg>
                    </span>

                    <span class="flex min-w-0 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-love-pink-100 text-love-pink-500 transition duration-500 ease-out" data-checkout-progress-icon>
                            @switch($number)
                                @case(1)
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5h10v9H4zM14 10h3.2l2.8 3v3.5h-6" />
                                        <circle cx="7" cy="17.5" r="1.5" />
                                        <circle cx="17.5" cy="17.5" r="1.5" />
                                    </svg>
                                    @break

                                @case(2)
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <rect x="4" y="6" width="16" height="12" rx="2" />
                                        <path stroke-linecap="round" d="M4 10h16M8 15h4" />
                                    </svg>
                                    @break

                                @case(3)
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 5h8M8 9h8M8 13h5M6 3.5h12v17l-3-2-3 2-3-2-3 2z" />
                                    </svg>
                                    @break

                                @default
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5 9.5 17 19 7" />
                                    </svg>
                            @endswitch
                        </span>
                        <span class="min-w-0 text-sm font-extrabold text-slate-700">{{ $label }}</span>
                    </span>
                </div>
            </li>
        @endforeach
    </ol>
</div>
