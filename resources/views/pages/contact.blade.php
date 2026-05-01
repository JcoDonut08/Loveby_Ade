@extends('layouts.guest')

@section('title', 'Contact Admin | Loveby_Ade')
@section('description', 'Contact the Loveby_Ade admin for orders, product questions, and support.')
@section('body_classes', 'bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_28%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_26%),linear-gradient(180deg,#fff7fb_0%,#eff8ff_52%,#fff8f3_100%)] text-slate-900')

@section('content')
    <div class="relative min-h-screen overflow-x-hidden">
        <x-home.store-header />

        <main class="py-10 sm:py-14">
            <section class="mx-auto max-w-[86rem] px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[0.82fr_1.18fr] lg:items-stretch">
                    <aside class="rounded-2xl border border-white/80 bg-white/92 p-6 shadow-[0_24px_58px_-42px_rgba(15,23,42,0.28)] sm:p-8">
                        <a class="inline-flex items-center gap-2 text-sm font-semibold text-love-blue-500 transition hover:text-love-pink-500" href="{{ route('home') }}">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11 6-6 6 6 6" />
                            </svg>
                            <span>Back to homepage</span>
                        </a>

                        <p class="mt-8 text-sm font-bold text-love-pink-500">Contact Admin</p>
                        <h1 class="mt-3 font-display text-4xl leading-tight text-slate-950 sm:text-5xl">Need help with your dessert order?</h1>
                        <p class="mt-5 text-base leading-8 text-slate-600">
                            Send the Loveby_Ade admin a message for order concerns, product questions, custom requests, and delivery help.
                        </p>

                        <div class="mt-8 grid gap-4">
                            <a class="group flex items-center gap-4 rounded-2xl border border-love-pink-100 bg-love-pink-100/55 p-4 transition hover:border-love-pink-200 hover:bg-love-pink-100" href="mailto:hello@lovebyade.test">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-love-pink-500">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.75h14.5v10.5H4.75z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 7.25 6.75 5 6.75-5" />
                                    </svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-bold text-slate-950">Email admin</span>
                                    <span class="mt-1 block text-sm text-slate-500 group-hover:text-slate-700">hello@lovebyade.test</span>
                                </span>
                            </a>

                            <div class="flex items-center gap-4 rounded-2xl border border-love-blue-100 bg-love-blue-100/55 p-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-love-blue-500">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.25 4.75h9.5a1.5 1.5 0 0 1 1.5 1.5v11.5a1.5 1.5 0 0 1-1.5 1.5h-9.5a1.5 1.5 0 0 1-1.5-1.5V6.25a1.5 1.5 0 0 1 1.5-1.5Z" />
                                        <path stroke-linecap="round" d="M10 16.75h4" />
                                    </svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-bold text-slate-950">Call or text</span>
                                    <span class="mt-1 block text-sm text-slate-500">+63 912 345 6789</span>
                                </span>
                            </div>

                            <div class="rounded-2xl border border-white/80 bg-white/70 p-4">
                                <p class="text-sm font-bold text-slate-950">Admin hours</p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">Open daily, 9:00 AM to 7:00 PM for order and product support.</p>
                            </div>
                        </div>
                    </aside>

                    <section class="rounded-2xl border border-white/80 bg-white/94 p-6 shadow-[0_24px_58px_-42px_rgba(15,23,42,0.28)] sm:p-8">
                        <div>
                            <p class="text-lg font-bold text-love-blue-500">Send a message</p>
                            <h2 class="mt-2 text-3xl font-bold text-slate-950">Contact form</h2>
                        </div>

                        <form class="mt-7 grid gap-5" data-contact-email="hello@lovebyade.test" data-contact-form>
                            <div class="grid gap-5 md:grid-cols-2">
                                <label class="grid gap-2 text-sm font-semibold text-slate-700">
                                    Full name
                                    <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="name" type="text" autocomplete="name" placeholder="Your name" required>
                                </label>

                                <label class="grid gap-2 text-sm font-semibold text-slate-700">
                                    Email address
                                    <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                                </label>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <label class="grid gap-2 text-sm font-semibold text-slate-700">
                                    Concern type
                                    <select class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="concern" required>
                                        <option value="">Choose a concern</option>
                                        <option>Order follow-up</option>
                                        <option>Product question</option>
                                        <option>Custom dessert request</option>
                                        <option>Payment or delivery help</option>
                                    </select>
                                </label>

                                <label class="grid gap-2 text-sm font-semibold text-slate-700">
                                    Order number
                                    <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="order_number" type="text" placeholder="Optional">
                                </label>
                            </div>

                            <label class="grid gap-2 text-sm font-semibold text-slate-700">
                                Message
                                <textarea class="min-h-44 resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-7 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="message" placeholder="Tell the admin how we can help." required></textarea>
                            </label>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-semibold text-love-blue-500 opacity-0" data-contact-form-status>Message ready for admin.</p>
                                <button class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="submit">
                                    Send message
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </section>
        </main>

        <x-home.store-footer />
    </div>
@endsection
