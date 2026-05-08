<section id="sales-performance" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-sales-performance data-sales-default="weekly">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">Sales performance</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]" data-sales-caption>Track your bakery's sweet revenue</p>
        </div>

        <div class="grid grid-cols-4 gap-1 rounded-full bg-[#f7f0ea] p-1">
            <button class="rounded-full px-4 py-2 text-sm font-extrabold text-[#9a6c7b] transition hover:text-love-pink-500" type="button" data-sales-filter="daily" data-sales-total="₱18,420" data-sales-caption="Today from 132 dessert orders" aria-pressed="false">Daily</button>
            <button class="rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[#512438] shadow-sm" type="button" data-sales-filter="weekly" data-sales-total="₱48,290" data-sales-caption="Track your bakery's sweet revenue" aria-pressed="true">Weekly</button>
            <button class="rounded-full px-4 py-2 text-sm font-extrabold text-[#9a6c7b] transition hover:text-love-pink-500" type="button" data-sales-filter="monthly" data-sales-total="₱312,850" data-sales-caption="This month across all sweet categories" aria-pressed="false">Monthly</button>
            <button class="rounded-full px-4 py-2 text-sm font-extrabold text-[#9a6c7b] transition hover:text-love-pink-500" type="button" data-sales-filter="yearly" data-sales-total="₱2.84M" data-sales-caption="Year-to-date revenue from repeat buyers" aria-pressed="false">Yearly</button>
        </div>
    </div>

    <div class="mt-8 overflow-x-auto">
        <div class="min-w-[42rem]">
            <div class="relative h-80 pl-16">
                <div class="absolute inset-y-0 left-0 grid w-12 grid-rows-5 text-right text-sm font-medium text-[#9a6c7b]">
                    <span>10000</span>
                    <span>7500</span>
                    <span>5000</span>
                    <span>2500</span>
                    <span>0</span>
                </div>

                <div class="absolute inset-y-2 left-16 right-0 grid grid-rows-5">
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                    <span class="border-t border-dashed border-love-pink-100"></span>
                </div>

                <div class="absolute bottom-0 left-16 right-0 grid h-[18.75rem] grid-cols-7 items-end gap-6" data-sales-bars="daily" hidden>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[136px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>8 AM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[170px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>10 AM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[118px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>12 PM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[206px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>2 PM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[154px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>4 PM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[224px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>6 PM</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[104px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>8 PM</div>
                </div>

                <div class="absolute bottom-0 left-16 right-0 grid h-[18.75rem] grid-cols-7 items-end gap-6" data-sales-bars="weekly">
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[126px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Mon</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[152px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Tue</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[144px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Wed</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[186px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Thu</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[252px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Fri</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[294px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Sat</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[226px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Sun</div>
                </div>

                <div class="absolute bottom-0 left-16 right-0 grid h-[18.75rem] grid-cols-6 items-end gap-6" data-sales-bars="monthly" hidden>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[158px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Week 1</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[194px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Week 2</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[236px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Week 3</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[288px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Week 4</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[226px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Week 5</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[172px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Promo</div>
                </div>

                <div class="absolute bottom-0 left-16 right-0 grid h-[18.75rem] grid-cols-4 items-end gap-6" data-sales-bars="yearly" hidden>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[172px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Q1</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[236px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Q2</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[296px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Q3</div>
                    <div class="grid gap-3 text-center text-sm font-medium text-[#9a6c7b]"><span class="h-[224px] rounded-t-xl bg-[linear-gradient(180deg,#f472a8_0%,#f9c6dd_100%)]"></span>Q4</div>
                </div>
            </div>
        </div>
    </div>
</section>
