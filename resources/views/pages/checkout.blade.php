@extends('layouts.guest')

@section('title', 'Checkout | Loveby_Ade')
@section('description', 'Complete Loveby_Ade checkout shipping details, payment method, order review, and confirmation.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff3f8_0%,#eff8ff_48%,#fff8f3_100%)] text-slate-900')

@php
    $steps = [
        1 => 'Shipping Details',
        2 => 'Payment Method',
        3 => 'Order Review',
        4 => 'Confirmation',
    ];
    $phoneDigits = function (?string $value): string {
        $digits = preg_replace('/\D/', '', (string) $value) ?: '';

        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return substr($digits, 1);
        }

        return $digits;
    };
@endphp

@section('content')
    <div class="relative min-h-screen overflow-x-hidden" data-checkout-page data-confirm-url="{{ route('orders.confirm') }}">
        <x-home.store-header />

        <main class="mx-auto max-w-[86rem] px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-white/80 bg-white/90 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                <p class="text-sm font-bold uppercase tracking-[0.26em] text-love-pink-500">Secure checkout</p>
                <h1 class="mt-2 font-display text-4xl text-slate-950 sm:text-5xl">Complete your order</h1>
                <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">Add delivery details, choose a payment method, and review your dessert box before placing the order.</p>
            </section>

            <div class="mt-6">
                <x-checkout.progress :steps="$steps" />
            </div>

            <form class="mt-8" method="POST" action="{{ route('checkout.store') }}" data-checkout-form>
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <section class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_25rem]" data-checkout-step="1">
                    <div class="rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                        <h2 class="text-2xl font-extrabold text-slate-950">Shipping Details</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Tell us where to send your freshly prepared treats.</p>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            <x-checkout.field id="full-name" label="Full name" name="full_name" :value="old('full_name', auth()->user()?->name)" placeholder="Ade Santos" required />
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700" for="contact-number">Contact number</label>
                                <span class="mt-2 flex items-center rounded-xl border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition focus-within:border-love-pink-300 focus-within:ring-4 focus-within:ring-love-pink-100">
                                    <span class="shrink-0 text-slate-900">+63-</span>
                                    <input class="min-w-0 flex-1 bg-transparent text-sm font-semibold text-slate-900 outline-none placeholder:text-slate-400" id="contact-number" name="contact_number_digits" type="tel" value="{{ old('contact_number_digits', $phoneDigits(auth()->user()?->contact_number)) }}" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="tel-national" placeholder="0000000000" required data-phone-digits data-phone-prefix="+63-" data-checkout-input="contact_number">
                                </span>
                                @if ($errors->has('contact_number_digits') || $errors->has('contact_number'))
                                    <span class="mt-2 block text-xs font-medium text-red-500">{{ $errors->first('contact_number_digits') ?: $errors->first('contact_number') }}</span>
                                @endif
                            </div>
                            <x-checkout.field id="email-address" label="Email address" name="email_address" :value="old('email_address', auth()->user()?->email)" type="email" placeholder="you@example.com" required />
                            <x-checkout.field id="complete-address" label="Complete address" name="complete_address" :value="old('complete_address', auth()->user()?->address)" placeholder="House, street, barangay, city" required />
                            <div class="sm:col-span-2">
                                <x-checkout.field id="delivery-notes" label="Delivery notes" name="delivery_notes" :value="old('delivery_notes')" placeholder="Gate color, landmark, preferred handoff notes" textarea />
                            </div>
                        </div>

                        <div class="mt-7 flex justify-end">
                            <button class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100 sm:w-auto" type="button" data-checkout-next>
                                Continue to Payment
                            </button>
                        </div>
                    </div>

                    <aside class="h-max rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                        <h2 class="text-xl font-extrabold text-slate-950">Order snapshot</h2>
                        <p class="mt-2 text-sm font-medium text-slate-500">{{ $cart['count'] }} {{ $cart['count'] === 1 ? 'item' : 'items' }} ready for checkout</p>
                        <div class="mt-5 space-y-3 border-y border-love-pink-100/80 py-5">
                            <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                                <span>Subtotal</span>
                                <span class="font-extrabold text-slate-950">{{ $cart['formatted_subtotal'] }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                                <span>Delivery fee</span>
                                <span class="font-extrabold text-slate-950">{{ $formattedDeliveryFee }}</span>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center justify-between gap-4">
                            <span class="font-extrabold text-slate-950">Total</span>
                            <span class="text-2xl font-extrabold text-love-pink-500">{{ $formattedTotal }}</span>
                        </div>
                    </aside>
                </section>

                <section class="hidden rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]" data-checkout-step="2">
                    <h2 class="text-2xl font-extrabold text-slate-950">Payment Method</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Choose how you would like to pay for this order.</p>

                    <div class="mt-6 grid gap-4 lg:grid-cols-3">
                        <x-checkout.payment-card method="gcash" title="GCash" description="Pay instantly with your mobile wallet before delivery." selected>
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="7" y="2.75" width="10" height="18.5" rx="2.2" />
                                <path stroke-linecap="round" d="M10 6.5h4M11 18h2" />
                            </svg>
                        </x-checkout.payment-card>
                        <x-checkout.payment-card method="paymaya" title="PayMaya" description="Use your PayMaya wallet or linked card at checkout.">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="2.5" />
                                <path stroke-linecap="round" d="M3.5 9.5h17M7 15h4" />
                            </svg>
                        </x-checkout.payment-card>
                        <x-checkout.payment-card method="cod" title="Cash on Delivery" description="Settle the payment when your desserts arrive.">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="3.25" y="6.5" width="17.5" height="11" rx="2" />
                                <circle cx="12" cy="12" r="2.5" />
                                <path stroke-linecap="round" d="M6.5 9.25v5.5M17.5 9.25v5.5" />
                            </svg>
                        </x-checkout.payment-card>
                    </div>

                    <input type="hidden" name="payment_method" value="GCash" data-selected-payment-input>

                    <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                        <button class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500 focus:outline-none focus:ring-4 focus:ring-love-blue-100" type="button" data-checkout-back>
                            Back
                        </button>
                        <button class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-checkout-next>
                            Continue to Review
                        </button>
                    </div>
                </section>

                <section class="hidden grid gap-8 lg:grid-cols-[minmax(0,1fr)_25rem]" data-checkout-step="3">
                    <div class="rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                        <h2 class="text-2xl font-extrabold text-slate-950">Order Review</h2>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <article class="rounded-2xl border border-love-pink-200 bg-[#fff7f9] p-5 shadow-[0_24px_58px_-42px_rgba(236,72,153,0.38)]">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full border border-love-pink-100 bg-white text-love-pink-500">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5h10v9H4zM14 10h3.2l2.8 3v3.5h-6" />
                                            <circle cx="7" cy="17.5" r="1.5" />
                                            <circle cx="17.5" cy="17.5" r="1.5" />
                                        </svg>
                                    </span>
                                    <h3 class="text-xs font-extrabold uppercase tracking-[0.32em] text-love-pink-500">Shipping</h3>
                                </div>

                                <dl class="mt-6 space-y-4 text-sm">
                                    <div class="grid grid-cols-[2.25rem_minmax(0,1fr)] gap-3">
                                        <dt class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-xs font-extrabold text-slate-500">Aa</dt>
                                        <dd class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Name</span>
                                            <span class="mt-1 block break-words font-extrabold text-slate-950" data-review-field="full_name">Not provided</span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-[2.25rem_minmax(0,1fr)] gap-3">
                                        <dt class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 4.5 9.6 7 8 9.2a12.5 12.5 0 0 0 6.8 6.8l2.2-1.6 2.5 2.6c.4.4.4 1 0 1.4l-1.1 1.1c-.7.7-1.8 1-2.8.7A17.5 17.5 0 0 1 3.8 8.4c-.3-1 0-2.1.7-2.8l1.1-1.1c.4-.4 1-.4 1.4 0Z" />
                                            </svg>
                                        </dt>
                                        <dd class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Contact</span>
                                            <span class="mt-1 block break-words font-extrabold text-slate-950" data-review-field="contact_number">Not provided</span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-[2.25rem_minmax(0,1fr)] gap-3">
                                        <dt class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <rect x="4" y="6" width="16" height="12" rx="2" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m5.5 8 6.5 5 6.5-5" />
                                            </svg>
                                        </dt>
                                        <dd class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Email</span>
                                            <span class="mt-1 block break-words font-extrabold text-slate-950" data-review-field="email_address">Not provided</span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-[2.25rem_minmax(0,1fr)] gap-3">
                                        <dt class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11Z" />
                                                <circle cx="12" cy="10" r="2" />
                                            </svg>
                                        </dt>
                                        <dd class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Address</span>
                                            <span class="mt-1 block break-words font-extrabold text-slate-950" data-review-field="complete_address">Not provided</span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-[2.25rem_minmax(0,1fr)] gap-3">
                                        <dt class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 6h14M5 11h14M5 16h9" />
                                            </svg>
                                        </dt>
                                        <dd class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Notes</span>
                                            <span class="mt-1 block break-words font-extrabold text-slate-950" data-review-field="delivery_notes">No delivery notes</span>
                                        </dd>
                                    </div>
                                </dl>
                            </article>

                            <article class="rounded-2xl border border-love-blue-200 bg-[#d8fbfb] p-5 shadow-[0_24px_58px_-42px_rgba(34,211,238,0.38)]">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-love-blue-100 text-love-blue-500">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                            <rect x="4" y="6" width="16" height="12" rx="2" />
                                            <path stroke-linecap="round" d="M4 10h16M8 15h4" />
                                        </svg>
                                    </span>
                                    <h3 class="text-xs font-extrabold uppercase tracking-[0.32em] text-love-blue-500">Payment</h3>
                                </div>

                                <p class="mt-7 font-display text-2xl font-bold text-slate-950" data-review-payment-title>GCash</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600" data-review-payment-description>Pay instantly with your mobile wallet before delivery.</p>

                                <div class="mt-6 flex items-center gap-3 rounded-full bg-white/70 px-4 py-3 text-sm font-semibold text-slate-500">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 7" />
                                        </svg>
                                    </span>
                                    <span data-review-payment-note>No upfront charges</span>
                                </div>
                            </article>
                        </div>

                        <div class="mt-7">
                            <h3 class="text-xl font-extrabold text-slate-950">Cart items</h3>
                            <div class="mt-3 divide-y divide-love-pink-100/80">
                                @forelse ($cart['items'] as $item)
                                    <x-checkout.review-item :item="$item" />
                                @empty
                                    <p class="rounded-xl border border-love-pink-100 bg-white p-4 text-sm font-semibold text-slate-500">Your cart is empty.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <aside class="h-max rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]">
                        <h3 class="text-xl font-extrabold text-slate-950">Payment summary</h3>
                        <label class="mt-6 block text-sm font-extrabold text-slate-700" for="checkout-promo-code">Promo code</label>
                        <div class="mt-2 flex gap-2">
                            <input class="min-w-0 flex-1 rounded-full border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="checkout-promo-code" type="text" placeholder="Enter code">
                            <button class="rounded-full bg-love-pink-100 px-5 text-sm font-extrabold text-love-pink-500 transition hover:bg-love-pink-200" type="button">Apply</button>
                        </div>

                        <dl class="mt-6 space-y-4 border-y border-love-pink-100/80 py-5">
                            <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                                <dt>Subtotal</dt>
                                <dd class="font-extrabold text-slate-950">{{ $cart['formatted_subtotal'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                                <dt>Discount</dt>
                                <dd class="font-extrabold text-slate-950">{{ $formattedDiscount }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-500">
                                <dt>Delivery fee</dt>
                                <dd class="font-extrabold text-slate-950">{{ $formattedDeliveryFee }}</dd>
                            </div>
                        </dl>

                        <div class="mt-5 flex items-center justify-between gap-4">
                            <span class="text-lg font-extrabold text-slate-950">Total</span>
                            <span class="text-2xl font-extrabold text-love-pink-500">{{ $formattedTotal }}</span>
                        </div>

                        <div class="mt-7 flex flex-col-reverse gap-3">
                            <button class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500 focus:outline-none focus:ring-4 focus:ring-love-blue-100" type="button" data-checkout-back>
                                Back
                            </button>
                            <button class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100 disabled:cursor-wait disabled:opacity-70" type="button" data-place-order>
                                Place Order
                            </button>
                        </div>
                    </aside>
                </section>

                <section class="hidden rounded-2xl border border-white/80 bg-white/92 p-8 text-center shadow-[0_24px_58px_-38px_rgba(15,23,42,0.32)]" data-checkout-step="4">
                    <div class="mx-auto flex h-24 w-24 scale-75 items-center justify-center rounded-full bg-love-pink-100 text-love-pink-500 opacity-0 shadow-[0_22px_54px_-32px_rgba(236,72,153,0.7)] transition duration-700 ease-out" data-confirmation-check>
                        <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5 9.5 17 19 7" />
                        </svg>
                    </div>
                    <p class="mt-7 text-sm font-bold uppercase tracking-[0.26em] text-love-pink-500">Confirmation</p>
                    <h2 class="mt-2 font-display text-4xl text-slate-950">Order has been placed</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-500">We are taking you to your order confirmation page now.</p>
                </section>
            </form>
        </main>

        <x-home.store-footer />
    </div>
@endsection
