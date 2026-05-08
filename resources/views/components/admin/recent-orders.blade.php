<section id="recent-orders" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
    <div class="flex flex-col gap-3 p-6 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">Recent orders</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]">Latest activity from your shop</p>
        </div>
        <a class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ route('admin.dashboard') }}#recent-orders">
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
                <tr>
                    <td class="py-5 pr-5 font-extrabold text-[#3b1728]">#LBA-3421</td>
                    <td class="px-5 py-5 font-bold text-[#512438]">Sophia Laurent</td>
                    <td class="px-5 py-5 font-medium text-[#9a6c7b]">Strawberry Cream Cake</td>
                    <td class="px-5 py-5 font-extrabold text-[#3b1728]">₱84.50</td>
                    <td class="py-5 pl-5"><span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-1.5 text-sm font-extrabold text-emerald-600"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Delivered</span></td>
                </tr>
                <tr>
                    <td class="py-5 pr-5 font-extrabold text-[#3b1728]">#LBA-3420</td>
                    <td class="px-5 py-5 font-bold text-[#512438]">Marcus Chen</td>
                    <td class="px-5 py-5 font-medium text-[#9a6c7b]">Glazed Donuts (6pc)</td>
                    <td class="px-5 py-5 font-extrabold text-[#3b1728]">₱56.00</td>
                    <td class="py-5 pl-5"><span class="inline-flex items-center gap-2 rounded-full bg-love-blue-200 px-4 py-1.5 text-sm font-extrabold text-[#23445c]"><span class="h-2 w-2 rounded-full bg-love-blue-400"></span>Shipped</span></td>
                </tr>
                <tr>
                    <td class="py-5 pr-5 font-extrabold text-[#3b1728]">#LBA-3419</td>
                    <td class="px-5 py-5 font-bold text-[#512438]">Amelia Brooks</td>
                    <td class="px-5 py-5 font-medium text-[#9a6c7b]">Funfetti Birthday Cake</td>
                    <td class="px-5 py-5 font-extrabold text-[#3b1728]">₱118.75</td>
                    <td class="py-5 pl-5"><span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-1.5 text-sm font-extrabold text-amber-700"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Preparing</span></td>
                </tr>
                <tr>
                    <td class="py-5 pr-5 font-extrabold text-[#3b1728]">#LBA-3418</td>
                    <td class="px-5 py-5 font-bold text-[#512438]">Liam O'Connor</td>
                    <td class="px-5 py-5 font-medium text-[#9a6c7b]">Cookie Sampler Box</td>
                    <td class="px-5 py-5 font-extrabold text-[#3b1728]">₱42.25</td>
                    <td class="py-5 pl-5"><span class="inline-flex items-center gap-2 rounded-full bg-love-pink-100 px-4 py-1.5 text-sm font-extrabold text-love-pink-500"><span class="h-2 w-2 rounded-full bg-love-pink-400"></span>Queued</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
