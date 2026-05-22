@props(['rows' => []])

@php
    $rowCount = count($rows);
    $visibleStart = $rowCount > 0 ? 1 : 0;
    $visibleEnd = min(6, $rowCount);
@endphp

<section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-analytics-table data-analytics-label="sales">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">Sales report</h2>
            <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Customers, ordered products, quantities, and totals.</p>
        </div>
        <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="analytics-sales-page-size">Rows<select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="analytics-sales-page-size" data-analytics-page-size><option value="6">6 rows</option><option value="9">9 rows</option><option value="12">12 rows</option></select></label>
    </div>

    <div class="mt-5 overflow-x-auto">
        <table class="w-full min-w-[46rem] border-separate border-spacing-y-2 text-left text-sm">
            <thead class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">
                <tr><th class="px-4 py-2">Customer</th><th class="px-4 py-2">Product ordered</th><th class="px-4 py-2 text-right">Quantity</th><th class="px-4 py-2 text-right">Total</th></tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr class="bg-love-cream" data-analytics-row><td class="rounded-l-xl px-4 py-3 font-extrabold text-[#3b1728]">{{ $row['customer'] }}</td><td class="px-4 py-3 text-[#9a6c7b]">{{ $row['product'] }}</td><td class="px-4 py-3 text-right font-extrabold text-[#512438]">{{ $row['quantity'] }}</td><td class="rounded-r-xl px-4 py-3 text-right font-extrabold text-[#512438]">&#8369;{{ $row['total'] }}</td></tr>
                @empty
                    <tr class="bg-love-cream" data-analytics-empty><td class="rounded-xl px-4 py-3 text-center font-extrabold text-[#9a6c7b]" colspan="4">No sales found for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <p class="text-sm font-semibold text-[#9a6c7b]" data-analytics-pagination-status>Showing {{ $visibleStart }}-{{ $visibleEnd }} of {{ $rowCount }} sales</p>
        <nav class="flex flex-wrap items-center gap-2" aria-label="Sales report pagination"><button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-analytics-page-previous>Previous</button><span class="flex flex-wrap items-center gap-2" data-analytics-page-buttons></span><button class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-analytics-page-next>Next</button></nav>
    </div>
</section>
