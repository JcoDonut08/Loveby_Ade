@props([
    'promotions' => collect(),
])

@php
    $promotions = collect($promotions);
    $fieldClass = 'mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80';
    $textareaClass = 'mt-2 min-h-28 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80';
@endphp

<section class="grid gap-6" data-admin-promotions>
    @if (session('status'))
        <div class="rounded-[1.25rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-extrabold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-[1.25rem] border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-extrabold text-rose-600">
            {{ $errors->first() }}
        </div>
    @endif

    <section id="new-promo" class="grid gap-5 rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] 2xl:grid-cols-[minmax(0,1.15fr)_minmax(22rem,0.85fr)]">
        <div>
            <h2 class="text-2xl font-extrabold text-[#3b1728]">New Promotion</h2>
            <p class="mt-1 text-base font-medium text-[#9a6c7b]">Choose a checkout discount or an image ad for the announcement board.</p>

            <form class="mt-5 grid gap-5" method="POST" action="{{ route('admin.promotions.store') }}" enctype="multipart/form-data" data-promotion-form>
                @csrf
                <input class="peer/discount sr-only" id="promotion-kind-discount" name="kind" type="radio" value="discount" @checked(old('kind', \App\Models\Promotion::KIND_DISCOUNT) === \App\Models\Promotion::KIND_DISCOUNT)>
                <input class="peer/ad sr-only" id="promotion-kind-ad" name="kind" type="radio" value="ad" @checked(old('kind') === \App\Models\Promotion::KIND_AD)>

                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="cursor-pointer rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4 transition peer-checked/discount:border-love-pink-300 peer-checked/discount:bg-love-pink-100/60" for="promotion-kind-discount">
                        <span class="block text-sm font-extrabold text-[#512438]">Discount code</span>
                        <span class="mt-1 block text-xs font-medium text-[#9a6c7b]">Uses the default announcement board and applies at checkout.</span>
                    </label>

                    <label class="cursor-pointer rounded-[1.25rem] border border-love-pink-100 bg-love-cream p-4 transition peer-checked/ad:border-love-pink-300 peer-checked/ad:bg-love-pink-100/60" for="promotion-kind-ad">
                        <span class="block text-sm font-extrabold text-[#512438]">Image ad</span>
                        <span class="mt-1 block text-xs font-medium text-[#9a6c7b]">Uploads one image slide for the announcement board.</span>
                    </label>
                </div>

                <div class="hidden gap-5 peer-checked/discount:grid md:grid-cols-2">
                    <label class="block" for="promo-code">
                        <span class="text-sm font-extrabold text-[#512438]">Promo code</span>
                        <input class="{{ $fieldClass }}" id="promo-code" name="code" type="text" value="{{ old('code') }}" placeholder="SWEET20">
                    </label>

                    <label class="block" for="promo-discount-type">
                        <span class="text-sm font-extrabold text-[#512438]">Discount type</span>
                        <select class="{{ $fieldClass }}" id="promo-discount-type" name="discount_type">
                            <option value="percentage" @selected(old('discount_type', 'percentage') === 'percentage')>Percentage</option>
                            <option value="fixed" @selected(old('discount_type') === 'fixed')>Fixed amount</option>
                        </select>
                    </label>

                    <label class="block" for="promo-discount-value">
                        <span class="text-sm font-extrabold text-[#512438]">Discount value</span>
                        <input class="{{ $fieldClass }}" id="promo-discount-value" name="discount_value" type="number" min="0.01" step="0.01" value="{{ old('discount_value') }}" placeholder="20">
                    </label>

                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="flex items-end gap-3 rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3" for="promo-is-active">
                        <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-400 focus:ring-love-pink-100" id="promo-is-active" name="is_active" type="checkbox" value="1" @checked(old('is_active', '1'))>
                        <span>
                            <span class="block text-sm font-extrabold text-[#512438]">Active promotion</span>
                            <span class="mt-0.5 block text-xs font-medium text-[#9a6c7b]">Active items can appear on the storefront.</span>
                        </span>
                    </label>

                    <label class="block" for="promo-starts-at">
                        <span class="text-sm font-extrabold text-[#512438]">Start date</span>
                        <input class="{{ $fieldClass }}" id="promo-starts-at" name="starts_at" type="date" value="{{ old('starts_at') }}">
                    </label>

                    <label class="block" for="promo-expires-at">
                        <span class="text-sm font-extrabold text-[#512438]">Expiry date</span>
                        <input class="{{ $fieldClass }}" id="promo-expires-at" name="expires_at" type="date" value="{{ old('expires_at') }}">
                    </label>
                </div>

                <label class="hidden peer-checked/discount:block" for="promo-announcement-title">
                    <span class="text-sm font-extrabold text-[#512438]">Announcement title</span>
                    <input class="{{ $fieldClass }}" id="promo-announcement-title" name="announcement_title" type="text" value="{{ old('announcement_title') }}" placeholder="Get 20% off during our dessert sale.">
                </label>

                <label class="hidden peer-checked/discount:block" for="promo-announcement-body">
                    <span class="text-sm font-extrabold text-[#512438]">Announcement text</span>
                    <textarea class="{{ $textareaClass }}" id="promo-announcement-body" name="announcement_body" placeholder="Use the code on selected cakes, pastries, and cookie boxes while the promo is active.">{{ old('announcement_body') }}</textarea>
                </label>

                <div class="hidden gap-5 peer-checked/discount:grid md:grid-cols-[minmax(0,0.7fr)_minmax(0,1fr)]">
                    <label class="block" for="promo-announcement-cta">
                        <span class="text-sm font-extrabold text-[#512438]">CTA label</span>
                        <input class="{{ $fieldClass }}" id="promo-announcement-cta" name="announcement_cta" type="text" value="{{ old('announcement_cta') }}" placeholder="Claim Offer">
                    </label>
                </div>

                <div class="hidden peer-checked/ad:block">
                    <label class="block" for="promo-image">
                        <span class="text-sm font-extrabold text-[#512438]">Announcement board image</span>
                        <input class="mt-2 block w-full rounded-full border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] file:mr-4 file:rounded-full file:border-0 file:bg-love-pink-400 file:px-4 file:py-2 file:text-sm file:font-extrabold file:text-white" id="promo-image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
                        <span class="mt-2 block text-xs font-medium text-[#9a6c7b]">This image becomes its own announcement slide. Upload up to 10 MB.</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-6 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                        </svg>
                        <span>Create promotion</span>
                    </button>
                </div>
            </form>
        </div>

        <aside class="rounded-[1.25rem] border border-love-pink-100 bg-[linear-gradient(135deg,#fff8fb_0%,#eff8ff_100%)] p-5">
            <p class="text-sm font-extrabold uppercase tracking-wide text-love-pink-500">Announcement Board</p>
            <h3 class="mt-4 font-display text-4xl leading-tight text-[#3b1728]">Promo creative preview</h3>
            <p class="mt-3 text-sm leading-6 text-[#9a6c7b]">The latest active promo can be used at checkout as soon as it is saved.</p>
            <div class="mt-6 rounded-[1.25rem] border border-white/80 bg-white/88 p-5 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <span class="inline-flex rounded-full bg-love-pink-100 px-3 py-1 text-xs font-extrabold text-love-pink-500">{{ old('code', 'SWEET20') }}</span>
                <p class="mt-4 text-2xl font-extrabold text-[#3b1728]">{{ old('discount_value', '20') }}% off</p>
                <p class="mt-2 text-sm leading-6 text-[#9a6c7b]">Active codes are validated by date and status during checkout.</p>
            </div>
        </aside>
    </section>

    <section class="overflow-hidden rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
        <div class="flex flex-col gap-4 border-b border-love-pink-100/80 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-[#3b1728]">Promo Codes</h2>
                <p class="mt-1 text-base font-medium text-[#9a6c7b]">{{ $promotions->count() }} saved {{ str('promo')->plural($promotions->count()) }}.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[78rem] text-left text-sm">
                <thead class="bg-love-cream text-[#9a6c7b]">
                    <tr>
                        <th class="px-5 py-4 font-extrabold">Promotion</th>
                        <th class="px-5 py-4 font-extrabold">Discount</th>
                        <th class="px-5 py-4 font-extrabold">Validity</th>
                        <th class="px-5 py-4 font-extrabold">Uses</th>
                        <th class="px-5 py-4 font-extrabold">Revenue</th>
                        <th class="px-5 py-4 font-extrabold">Status</th>
                        <th class="px-5 py-4 text-right font-extrabold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-love-pink-100/80">
                    @forelse ($promotions as $promotion)
                        @php
                            $status = $promotion->statusLabel();
                            $statusClass = $status === 'Active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500';
                            $uses = $promotion->orders_count ?? 0;
                            $revenue = (float) ($promotion->orders_sum_total ?? 0);
                            $isImageAd = $promotion->kind === \App\Models\Promotion::KIND_AD;
                        @endphp

                        <tr class="align-middle">
                            <td class="px-5 py-5">
                                <span class="inline-flex rounded-full bg-love-pink-100 px-3 py-1 text-sm font-extrabold text-love-pink-500">
                                    {{ $isImageAd ? 'Image Ad' : $promotion->code }}
                                </span>
                                @if ($promotion->image_path)
                                    <span class="mt-2 block text-xs font-bold text-[#9a6c7b]">Image ad uploaded</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ $promotion->discountLabel() }}</td>
                            <td class="px-5 py-5 text-[#9a6c7b]">{{ $promotion->validityLabel() }}</td>
                            <td class="px-5 py-5 font-extrabold text-[#3b1728]">{{ number_format($uses) }}</td>
                            <td class="px-5 py-5 font-extrabold {{ $isImageAd ? 'text-[#9a6c7b]' : 'text-love-pink-400' }}">
                                @if ($isImageAd)
                                    No revenue
                                @else
                                    &#8369;{{ number_format($revenue, 2) }}
                                @endif
                            </td>
                            <td class="px-5 py-5">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold {{ $statusClass }}">
                                    <span class="h-2 w-2 rounded-full bg-current"></span>
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-5 py-5">
                                <div class="flex items-center justify-end gap-3">
                                    <input class="peer/edit-modal sr-only" id="promo-edit-modal-{{ $promotion->id }}" type="checkbox">
                                    <input class="peer/email-modal sr-only" id="promo-email-modal-{{ $promotion->code }}" type="checkbox">

                                    <form method="POST" action="{{ route('admin.promotions.toggle', $promotion) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="relative inline-flex h-8 w-14 items-center rounded-full transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $promotion->is_active ? 'bg-love-pink-400' : 'bg-love-pink-100' }}" type="submit" role="switch" aria-checked="{{ $promotion->is_active ? 'true' : 'false' }}" aria-label="{{ $promotion->is_active ? 'Deactivate' : 'Activate' }} {{ $promotion->code }}" title="{{ $promotion->is_active ? 'Active' : 'Inactive' }}">
                                            <span class="h-6 w-6 rounded-full bg-white shadow transition {{ $promotion->is_active ? 'translate-x-7' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>

                                    <label class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-love-pink-100 bg-white text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-edit-modal-{{ $promotion->id }}" aria-label="Edit {{ $promotion->code }} schedule" title="Edit schedule">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 18.25h12.5M6.75 15.25l.75-3 7.5-7.5 3 3-7.5 7.5-3 .75Z" />
                                        </svg>
                                    </label>

                                    @if ($promotion->kind === \App\Models\Promotion::KIND_DISCOUNT)
                                        <label class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-love-pink-100 bg-white text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-email-modal-{{ $promotion->code }}" aria-label="Email {{ $promotion->code }}" title="Email {{ $promotion->code }}">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.75h14.5v10.5H4.75z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 7.25 6.75 5 6.75-5" />
                                            </svg>
                                        </label>
                                    @endif

                                    <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-500 transition hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-rose-100" type="submit" aria-label="Delete {{ $promotion->code }}" title="Delete {{ $promotion->code }}">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                                            </svg>
                                        </button>
                                    </form>

                                    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6 peer-checked/edit-modal:flex">
                                        <label class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" for="promo-edit-modal-{{ $promotion->id }}" aria-label="Close schedule editor"></label>

                                        <section class="relative w-full max-w-lg rounded-[1.25rem] border border-love-pink-100 bg-white p-6 text-left shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Edit Schedule</p>
                                                    <h3 class="mt-1 text-2xl font-extrabold text-[#3b1728]">{{ $promotion->kind === \App\Models\Promotion::KIND_AD ? 'Image Ad' : $promotion->code }}</h3>
                                                </div>

                                                <label class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-edit-modal-{{ $promotion->id }}" aria-label="Close schedule editor" title="Close">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                                                    </svg>
                                                </label>
                                            </div>

                                            <form class="mt-5 grid gap-4" method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
                                                @csrf
                                                @method('PATCH')

                                                <label class="flex items-end gap-3 rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3" for="promo-edit-is-active-{{ $promotion->id }}">
                                                    <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-400 focus:ring-love-pink-100" id="promo-edit-is-active-{{ $promotion->id }}" name="is_active" type="checkbox" value="1" @checked($promotion->is_active)>
                                                    <span>
                                                        <span class="block text-sm font-extrabold text-[#512438]">Active promotion</span>
                                                        <span class="mt-0.5 block text-xs font-medium text-[#9a6c7b]">Inactive items stay hidden from customers.</span>
                                                    </span>
                                                </label>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <label class="block" for="promo-edit-starts-at-{{ $promotion->id }}">
                                                        <span class="text-sm font-extrabold text-[#512438]">Start date</span>
                                                        <input class="{{ $fieldClass }}" id="promo-edit-starts-at-{{ $promotion->id }}" name="starts_at" type="date" value="{{ $promotion->starts_at?->format('Y-m-d') }}">
                                                    </label>

                                                    <label class="block" for="promo-edit-expires-at-{{ $promotion->id }}">
                                                        <span class="text-sm font-extrabold text-[#512438]">Expiry date</span>
                                                        <input class="{{ $fieldClass }}" id="promo-edit-expires-at-{{ $promotion->id }}" name="expires_at" type="date" value="{{ $promotion->expires_at?->format('Y-m-d') }}">
                                                    </label>
                                                </div>

                                                <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">
                                                    Save schedule
                                                </button>
                                            </form>
                                        </section>
                                    </div>

                                    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6 peer-checked/email-modal:flex">
                                        <label class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" for="promo-email-modal-{{ $promotion->code }}" aria-label="Close email modal"></label>

                                        <section class="relative w-full max-w-md rounded-[1.25rem] border border-love-pink-100 bg-white p-6 text-left shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Promo Email</p>
                                                    <h3 class="mt-1 text-2xl font-extrabold text-[#3b1728]">{{ $promotion->code }}</h3>
                                                </div>

                                                <label class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus-within:ring-4 focus-within:ring-love-pink-100" for="promo-email-modal-{{ $promotion->code }}" aria-label="Close email modal" title="Close">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                                                    </svg>
                                                </label>
                                            </div>

                                            <form class="mt-5 grid gap-4" method="POST" action="{{ route('admin.promotions.email', $promotion) }}">
                                                @csrf

                                                <label class="block" for="promo-email-recipient-{{ $promotion->code }}">
                                                    <span class="text-sm font-extrabold text-[#512438]">Customer email</span>
                                                    <input class="{{ $fieldClass }}" id="promo-email-recipient-{{ $promotion->code }}" name="email" type="email" placeholder="customer@example.com" required>
                                                </label>

                                                <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">
                                                    Send promo code
                                                </button>
                                            </form>
                                        </section>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-8 text-sm font-semibold text-[#9a6c7b]" colspan="7">No promo codes yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</section>
