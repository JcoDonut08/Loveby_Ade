@props(['item'])

<article class="grid grid-cols-[4.5rem_minmax(0,1fr)] gap-4 border-b border-love-pink-100/80 py-4 sm:grid-cols-[5.5rem_minmax(0,1fr)_auto]">
    <img class="aspect-square w-full rounded-xl bg-slate-100 object-cover" src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">

    <div class="min-w-0">
        <h3 class="text-sm font-extrabold text-slate-950">{{ $item['title'] }}</h3>
        <p class="mt-1 text-sm font-medium text-slate-500">Qty {{ $item['quantity'] }}</p>
        <p class="mt-1 text-sm font-semibold text-love-orange-500">{{ $item['formatted_price'] }}</p>
    </div>

    <div class="col-span-2 flex items-center justify-between gap-4 text-sm font-semibold text-slate-500 sm:col-span-1 sm:block sm:text-right">
        <span>Subtotal</span>
        <span class="block text-base font-extrabold text-slate-950">{{ $item['formatted_line_total'] }}</span>
    </div>
</article>
