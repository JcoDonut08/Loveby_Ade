@props([
    'orders' => [],
])

<section id="recent-orders" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div class="flex flex-col gap-3 p-6 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">Recent orders</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]">Latest activity from your shop</p>
        </div>
        <a class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ route('admin.orders') }}">
            View all
        </a>
    </div>

    <div class="overflow-x-auto px-6 pb-6">
        <table class="min-w-[46rem] w-full border-collapse text-left text-sm">
            <thead>
                <tr class="border-b border-love-pink-100 text-[#9a6c7b]">
                    <th class="py-4 pr-5 font-extrabold">Order</th>
                    <th class="px-5 py-4 font-extrabold">Customer</th>
                    <th class="px-5 py-4 font-extrabold">Item</th>
                    <th class="px-5 py-4 font-extrabold">Total</th>
                    <th class="py-4 pl-5 font-extrabold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-love-pink-100/80">
                @forelse ($orders as $order)
                    <tr>
                        <td class="py-5 pr-5 font-extrabold text-[#3b1728]">{{ $order['number'] }}</td>
                        <td class="px-5 py-5 font-bold text-[#512438]">{{ $order['customer'] }}</td>
                        <td class="px-5 py-5 font-medium text-[#9a6c7b]">{{ $order['item'] }}</td>
                        <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ $order['total'] }}</td>
                        <td class="py-5 pl-5"><span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-extrabold {{ $order['statusTone']['badge'] }}"><span class="h-2 w-2 rounded-full {{ $order['statusTone']['dot'] }}"></span>{{ $order['status'] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-5 pr-5 font-extrabold text-[#3b1728]" colspan="5">No orders yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
