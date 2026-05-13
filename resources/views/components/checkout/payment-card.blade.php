@props([
    'method',
    'title',
    'description',
    'selected' => false,
])

<button
    class="group grid w-full grid-cols-[3rem_minmax(0,1fr)] gap-4 rounded-xl border bg-white p-4 text-left shadow-[0_18px_42px_-34px_rgba(15,23,42,0.28)] transition hover:-translate-y-0.5 hover:border-love-pink-200 hover:shadow-[0_24px_54px_-34px_rgba(236,72,153,0.34)] focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $selected ? 'border-love-pink-300 bg-love-pink-100/40 shadow-[0_24px_54px_-34px_rgba(236,72,153,0.52)]' : 'border-white/80' }}"
    type="button"
    aria-pressed="{{ $selected ? 'true' : 'false' }}"
    data-payment-card
    data-payment-method="{{ $method }}"
    data-payment-title="{{ $title }}"
    data-payment-description="{{ $description }}"
>
    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-love-pink-100 text-love-pink-500 transition group-hover:bg-love-pink-200">
        {{ $slot }}
    </span>
    <span class="min-w-0">
        <span class="block text-base font-extrabold text-slate-950">{{ $title }}</span>
        <span class="mt-1 block text-sm leading-6 text-slate-500">{{ $description }}</span>
    </span>
</button>
