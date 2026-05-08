@extends('layouts.admin')

@section('title', 'Chat Inbox | Loveby_Ade Admin')
@section('description', 'Review AI-assisted customer conversations and dessert order requests.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 18.25 6 14.75a7 7 0 1 1 3.25 3.25l-4.5.25Z" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Chat Inbox</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">AI-assisted customer conversations for orders, delivery, and custom desserts.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-chat-global-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="11" cy="11" r="6.5" />
                                <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                            </svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-chat-global-search" type="search" placeholder="Search chats, customers, orders...">
                    </label>

                    <button class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" />
                        </svg>
                        <span class="absolute -right-0.5 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-[#512438]">3</span>
                    </button>

                    <div class="flex h-12 shrink-0 items-center gap-3 rounded-full border border-love-pink-100 bg-white/88 py-1 pl-1 pr-4 shadow-[0_18px_35px_-28px_rgba(81,36,56,0.35)]">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">AD</span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-extrabold leading-tight text-[#512438]">Ade Sweet</span>
                            <span class="block text-xs font-medium leading-tight text-[#9a6c7b]">Admin</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 lg:px-10">
            <section class="grid min-h-[calc(100vh-8.5rem)] overflow-hidden rounded-[1.25rem] border border-love-pink-100/80 bg-white/96 shadow-[0_24px_65px_-45px_rgba(81,36,56,0.48)] xl:grid-cols-[23rem_minmax(0,1fr)]" data-admin-chat-inbox>
                <aside class="border-b border-love-pink-100 bg-white xl:border-b-0 xl:border-r">
                    <div class="border-b border-love-pink-100 p-4">
                        <label class="relative block" for="chat-inbox-search">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6.5" />
                                    <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                                </svg>
                            </span>
                            <input class="h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="chat-inbox-search" type="search" placeholder="Search chats...">
                        </label>
                    </div>

                    <div class="grid max-h-[28rem] overflow-y-auto xl:max-h-none">
                        <button class="grid gap-2 border-b border-love-pink-100 bg-love-pink-100/50 py-4 pl-6 pr-4 text-left transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-current="true">
                            <span class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">SL</span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="truncate text-base font-extrabold text-[#3b1728]">Sophia Laurent</span>
                                        <span class="shrink-0 text-xs font-semibold text-[#9a6c7b]">now</span>
                                    </span>
                                    <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Can I add a custom message on the cake?</span>
                                </span>
                            </span>
                            <span class="flex items-center justify-between gap-3 pl-14">
                                <span class="inline-flex h-7 items-center rounded-full bg-love-blue-100 px-3 text-xs font-extrabold text-[#1f6f8b]">AI ACTIVE</span>
                                <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-love-blue-300 px-2 text-xs font-extrabold text-[#512438]">2</span>
                            </span>
                        </button>

                        <button class="grid gap-2 border-b border-love-pink-100 py-4 pl-6 pr-4 text-left transition hover:bg-love-pink-100/60 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                            <span class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">MC</span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="truncate text-base font-extrabold text-[#3b1728]">Marcus Chen</span>
                                        <span class="shrink-0 text-xs font-semibold text-[#9a6c7b]">1h</span>
                                    </span>
                                    <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Thanks, donuts arrived perfect!</span>
                                </span>
                            </span>
                            <span class="pl-14">
                                <span class="inline-flex h-7 items-center rounded-full bg-love-blue-100 px-3 text-xs font-extrabold text-[#1f6f8b]">AI ACTIVE</span>
                            </span>
                        </button>

                        <button class="grid gap-2 border-b border-love-pink-100 py-4 pl-6 pr-4 text-left transition hover:bg-love-pink-100/60 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                            <span class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">AB</span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="truncate text-base font-extrabold text-[#3b1728]">Amelia Brooks</span>
                                        <span class="shrink-0 text-xs font-semibold text-[#9a6c7b]">2h</span>
                                    </span>
                                    <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">I would like to schedule delivery for Saturday.</span>
                                </span>
                            </span>
                            <span class="flex items-center justify-between gap-3 pl-14">
                                <span class="inline-flex h-7 items-center rounded-full bg-love-blue-100 px-3 text-xs font-extrabold text-[#1f6f8b]">AI ACTIVE</span>
                                <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-love-blue-300 px-2 text-xs font-extrabold text-[#512438]">1</span>
                            </span>
                        </button>

                        <button class="grid gap-2 py-4 pl-6 pr-4 text-left transition hover:bg-love-pink-100/60 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                            <span class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">LO</span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="truncate text-base font-extrabold text-[#3b1728]">Liam O'Connor</span>
                                        <span class="shrink-0 text-xs font-semibold text-[#9a6c7b]">Yesterday</span>
                                    </span>
                                    <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Do you have gluten-free cookies?</span>
                                </span>
                            </span>
                            <span class="flex items-center justify-between gap-3 pl-14">
                                <span class="inline-flex h-7 items-center rounded-full bg-love-blue-100 px-3 text-xs font-extrabold text-[#1f6f8b]">AI ACTIVE</span>
                                <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-love-blue-300 px-2 text-xs font-extrabold text-[#512438]">1</span>
                            </span>
                        </button>
                    </div>
                </aside>

                <section class="grid min-h-[34rem] grid-rows-[auto_minmax(0,1fr)_auto] bg-[#fff7fa]">
                    <div class="flex flex-col gap-4 border-b border-love-pink-100 bg-white px-5 py-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-sm font-extrabold text-white">SL</span>
                            <span class="min-w-0">
                                <span class="block truncate text-lg font-extrabold text-[#3b1728]">Sophia Laurent</span>
                                <span class="mt-1 inline-flex h-7 items-center rounded-full bg-love-blue-100 px-3 text-xs font-extrabold text-[#1f6f8b]">AI ACTIVE</span>
                            </span>
                        </div>

                        <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.75 19.25a5 5 0 0 1 10 0" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.25 8.75v5.5M13.5 11.5H19" />
                            </svg>
                            Take over from AI
                        </button>
                    </div>

                    <div class="space-y-5 overflow-y-auto px-4 py-5 sm:px-6">
                        <div class="flex items-end gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-[#512438] shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6.75 19.25a5.25 5.25 0 0 1 10.5 0" />
                                </svg>
                            </span>
                            <div class="max-w-[36rem] rounded-[1.2rem] rounded-bl-md bg-white px-5 py-3 text-sm font-medium leading-6 text-[#512438] shadow-sm">
                                <p>Hi! I just placed order #LBA-3421.</p>
                                <p class="mt-2 text-xs font-semibold text-[#9a6c7b]">10:21</p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <div class="max-w-[36rem] rounded-[1.2rem] rounded-br-md bg-love-blue-300 px-5 py-3 text-sm font-semibold leading-6 text-[#17324d] shadow-sm">
                                <p>Hi Sophia! I see your order. How can I help?</p>
                                <p class="mt-2 text-xs font-bold text-[#315b78]">10:21</p>
                            </div>
                        </div>

                        <div class="flex items-end gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-[#512438] shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6.75 19.25a5.25 5.25 0 0 1 10.5 0" />
                                </svg>
                            </span>
                            <div class="max-w-[36rem] rounded-[1.2rem] rounded-bl-md bg-white px-5 py-3 text-sm font-medium leading-6 text-[#512438] shadow-sm">
                                <p>Can I add a custom message on the cake?</p>
                                <p class="mt-2 text-xs font-semibold text-[#9a6c7b]">10:23</p>
                            </div>
                        </div>

                        <div class="flex items-end gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-[#512438] shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6.75 19.25a5.25 5.25 0 0 1 10.5 0" />
                                </svg>
                            </span>
                            <div class="max-w-[31rem] rounded-[1.2rem] rounded-bl-md bg-white px-5 py-3 text-sm font-medium leading-6 text-[#512438] shadow-sm">
                                <p>It's for my mom's birthday cake.</p>
                                <p class="mt-2 text-xs font-semibold text-[#9a6c7b]">10:23</p>
                            </div>
                        </div>
                    </div>

                    <form class="flex items-center gap-3 border-t border-love-pink-100 bg-white px-4 py-4 sm:px-5">
                        <label class="sr-only" for="chat-reply">Type your reply</label>
                        <input class="h-12 min-w-0 flex-1 rounded-full border border-love-pink-100 bg-white px-5 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="chat-reply" type="text" placeholder="Type your reply...">
                        <button class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Send reply">
                            <svg class="h-5 w-5 -translate-x-px translate-y-px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20 4.75-8.25 14.5-1.5-6.5-5.5-3 15.25-5Z" />
                            </svg>
                        </button>
                    </form>
                </section>

            </section>
        </main>
    </div>
@endsection
