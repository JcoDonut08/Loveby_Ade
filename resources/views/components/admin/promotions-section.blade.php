@php
    $promotions = collect([
        [
            'code' => 'SWEET20',
            'discount' => '20% off',
            'validity' => '2026-04-01 to 2026-05-31',
            'uses' => 142,
            'revenue' => 'PHP 3,820',
            'status' => 'Active',
            'is_active' => true,
            'has_image' => true,
        ],
        [
            'code' => 'DONUTLOVE',
            'discount' => 'PHP 50 off',
            'validity' => '2026-04-15 to 2026-05-15',
            'uses' => 89,
            'revenue' => 'PHP 1,240',
            'status' => 'Active',
            'is_active' => true,
            'has_image' => false,
        ],
        [
            'code' => 'FIRSTBITE',
            'discount' => '15% off',
            'validity' => '2026-01-01 to 2026-12-31',
            'uses' => 312,
            'revenue' => 'PHP 6,890',
            'status' => 'Active',
            'is_active' => true,
            'has_image' => false,
        ],
        [
            'code' => 'SUMMER10',
            'discount' => '10% off',
            'validity' => '2025-06-01 to 2025-08-31',
            'uses' => 421,
            'revenue' => 'PHP 8,420',
            'status' => 'Inactive',
            'is_active' => false,
            'has_image' => true,
        ],
    ]);

    $fieldClass = 'mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80';
    $textareaClass = 'mt-2 min-h-28 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80';
@endphp

<section class="grid gap-6" data-admin-promotions>
    <section id="new-promo" class="grid gap-5 rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] 2xl:grid-cols-[minmax(0,1.15fr)_minmax(22rem,0.85fr)]">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">New Promo Code</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]">Front-end draft UI for codes, discount amounts, dates, image ads, and email-ready messaging.</p>

            <form class="mt-5 grid gap-5" data-promotion-form>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block" for="promo-code">
                        <span class="text-sm font-extrabold text-[#512438]">Promo code</span>
                        <input class="{{ $fieldClass }}" id="promo-code" type="text" value="SWEET20" placeholder="SWEET20">
                    </label>

                    <label class="block" for="promo-discount-type">
                        <span class="text-sm font-extrabold text-[#512438]">Discount type</span>
                        <select class="{{ $fieldClass }}" id="promo-discount-type">
                            <option selected>Percentage</option>
                            <option>Fixed amount</option>
                        </select>
                    </label>

                    <label class="block" for="promo-discount-value">
                        <span class="text-sm font-extrabold text-[#512438]">Discount value</span>
                        <input class="{{ $fieldClass }}" id="promo-discount-value" type="number" min="0.01" step="0.01" value="20">
                    </label>

                    <label class="flex items-end gap-3 rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3" for="promo-is-active">
                        <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-400 focus:ring-love-pink-100" id="promo-is-active" type="checkbox" checked>
                        <span>
                            <span class="block text-sm font-extrabold text-[#512438]">Active promo</span>
                            <span class="mt-0.5 block text-xs font-medium text-[#9a6c7b]">Mock status toggle only.</span>
                        </span>
                    </label>

                    <label class="block" for="promo-starts-at">
                        <span class="text-sm font-extrabold text-[#512438]">Start date</span>
                        <input class="{{ $fieldClass }}" id="promo-starts-at" type="date" value="2026-04-01">
                    </label>

                    <label class="block" for="promo-expires-at">
                        <span class="text-sm font-extrabold text-[#512438]">Expiry date</span>
                        <input class="{{ $fieldClass }}" id="promo-expires-at" type="date" value="2026-05-31">
                    </label>
                </div>

                <label class="block" for="promo-announcement-title">
                    <span class="text-sm font-extrabold text-[#512438]">Announcement title</span>
                    <input class="{{ $fieldClass }}" id="promo-announcement-title" type="text" value="Get 20% off during our dessert sale.">
                </label>

                <label class="block" for="promo-announcement-body">
                    <span class="text-sm font-extrabold text-[#512438]">Announcement text</span>
                    <textarea class="{{ $textareaClass }}" id="promo-announcement-body">Use the code on selected cakes, pastries, and cookie boxes while the promo is active.</textarea>
                </label>

                <div class="grid gap-5 md:grid-cols-[minmax(0,0.7fr)_minmax(0,1fr)]">
                    <label class="block" for="promo-announcement-cta">
                        <span class="text-sm font-extrabold text-[#512438]">CTA label</span>
                        <input class="{{ $fieldClass }}" id="promo-announcement-cta" type="text" value="Claim Offer">
                    </label>

                    <label class="block" for="promo-image">
                        <span class="text-sm font-extrabold text-[#512438]">Image ad</span>
                        <input class="mt-2 block w-full rounded-full border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] file:mr-4 file:rounded-full file:border-0 file:bg-love-pink-400 file:px-4 file:py-2 file:text-sm file:font-extrabold file:text-white" id="promo-image" type="file" accept="image/png,image/jpeg,image/webp">
                    </label>
                </div>

                <div class="flex justify-end">
                    <button class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-6 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                        </svg>
                        <span>Create draft</span>
                    </button>
                </div>
            </form>
        </div>

        <aside class="rounded-[1.25rem] border border-love-pink-100 bg-[linear-gradient(135deg,#fff8fb_0%,#eff8ff_100%)] p-5">
            <p class="text-sm font-extrabold uppercase tracking-wide text-love-pink-500">Announcement Board</p>
            <h3 class="mt-4 font-display text-4xl leading-tight text-[#3b1728]">Promo creative preview</h3>
            <p class="mt-3 text-sm leading-6 text-[#9a6c7b]">Use an image upload for a custom ad or keep the text fields for the default soft board design.</p>
            <div class="mt-6 rounded-[1.25rem] border border-white/80 bg-white/88 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <span class="inline-flex rounded-full bg-love-pink-100 px-3 py-1 text-xs font-extrabold text-love-pink-500">SWEET20</span>
                <p class="mt-4 text-2xl font-extrabold text-[#3b1728]">20% off</p>
                <p class="mt-2 text-sm leading-6 text-[#9a6c7b]">This is the front-end draft of what can be shown on the storefront announcement board.</p>
            </div>
        </aside>
    </section>

    <section class="overflow-hidden rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
        <div class="flex flex-col gap-4 border-b border-love-pink-100/80 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-[#3b1728]">Promo Codes</h2>
                <p class="mt-1 text-base font-medium text-[#9a6c7b]">{{ $promotions->count() }} front-end promo drafts.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[78rem] text-left text-sm">
                <thead class="bg-love-cream text-[#9a6c7b]">
                    <tr>
                        <th class="px-5 py-4 font-extrabold">Code</th>
                        <th class="px-5 py-4 font-extrabold">Discount</th>
                        <th class="px-5 py-4 font-extrabold">Validity</th>
                        <th class="px-5 py-4 font-extrabold">Uses</th>
                        <th class="px-5 py-4 font-extrabold">Revenue</th>
                        <th class="px-5 py-4 font-extrabold">Status</th>
                        <th class="px-5 py-4 text-right font-extrabold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-love-pink-100/80">
                    @foreach ($promotions as $promotion)
                        @php
                            $statusClass = $promotion['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500';
                        @endphp

                        <tr class="align-middle">
                            <td class="px-5 py-5">
                                <span class="inline-flex rounded-full bg-love-pink-100 px-3 py-1 text-sm font-extrabold text-love-pink-500">{{ $promotion['code'] }}</span>
                                @if ($promotion['has_image'])
                                    <span class="mt-2 block text-xs font-bold text-[#9a6c7b]">Image ad draft</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ $promotion['discount'] }}</td>
                            <td class="px-5 py-5 text-[#9a6c7b]">{{ $promotion['validity'] }}</td>
                            <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ $promotion['uses'] }}</td>
                            <td class="px-5 py-5 font-extrabold text-love-pink-400">{{ $promotion['revenue'] }}</td>
                            <td class="px-5 py-5">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold {{ $statusClass }}">
                                    <span class="h-2 w-2 rounded-full bg-current"></span>
                                    {{ $promotion['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-5">
                                <div class="flex items-center justify-end gap-3">
                                    <input class="peer/email-modal sr-only" id="promo-email-modal-{{ $promotion['code'] }}" type="checkbox">

                                    <button class="relative inline-flex h-8 w-14 items-center rounded-full transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $promotion['is_active'] ? 'bg-love-pink-400' : 'bg-love-pink-100' }}" type="button" role="switch" aria-checked="{{ $promotion['is_active'] ? 'true' : 'false' }}" aria-label="{{ $promotion['is_active'] ? 'Deactivate' : 'Activate' }} {{ $promotion['code'] }}" title="{{ $promotion['is_active'] ? 'Active' : 'Inactive' }}">
                                        <span class="h-6 w-6 rounded-full bg-white shadow transition {{ $promotion['is_active'] ? 'translate-x-7' : 'translate-x-1' }}"></span>
                                    </button>

                                    <details class="group relative">
                                        <summary class="inline-flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full border border-love-pink-100 bg-white text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100 [&::-webkit-details-marker]:hidden" aria-label="Manage {{ $promotion['code'] }}" title="Manage {{ $promotion['code'] }}">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75h.01M12 12h.01M12 17.25h.01" />
                                            </svg>
                                        </summary>

                                        <div class="absolute right-0 z-30 mt-3 grid min-w-28 grid-cols-2 gap-2 rounded-[1.25rem] border border-love-pink-100 bg-white p-3 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
                                            <label class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-love-pink-100 bg-white text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-email-modal-{{ $promotion['code'] }}" aria-label="Email {{ $promotion['code'] }}" title="Email {{ $promotion['code'] }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.75h14.5v10.5H4.75z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 7.25 6.75 5 6.75-5" />
                                                </svg>
                                            </label>

                                            <button class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-500 transition hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-rose-100" type="button" aria-label="Delete {{ $promotion['code'] }}" title="Delete {{ $promotion['code'] }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                                                </svg>
                                            </button>
                                        </div>
                                    </details>

                                    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6 peer-checked/email-modal:flex">
                                        <label class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" for="promo-email-modal-{{ $promotion['code'] }}" aria-label="Close email modal"></label>

                                        <section class="relative w-full max-w-md rounded-[1.25rem] border border-love-pink-100 bg-white p-6 text-left shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Promo Email</p>
                                                    <h3 class="mt-1 text-2xl font-extrabold text-[#3b1728]">{{ $promotion['code'] }}</h3>
                                                </div>

                                                <label class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-email-modal-{{ $promotion['code'] }}" aria-label="Close email modal" title="Close">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                                                    </svg>
                                                </label>
                                            </div>

                                            <div class="mt-5 grid gap-4">
                                                <label class="block" for="promo-email-recipient-{{ $promotion['code'] }}">
                                                    <span class="text-sm font-extrabold text-[#512438]">Customer email</span>
                                                    <input class="{{ $fieldClass }}" id="promo-email-recipient-{{ $promotion['code'] }}" type="email" placeholder="customer@example.com">
                                                </label>

                                                <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                                                    Send promo code
                                                </button>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</section>
