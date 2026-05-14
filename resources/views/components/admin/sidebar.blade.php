@php
    $isDashboardRoute = request()->routeIs('admin.dashboard');
    $isOrdersRoute = request()->routeIs('admin.orders');
    $isProductsRoute = request()->routeIs('admin.products');
    $isCustomersRoute = request()->routeIs('admin.customers');
    $isPromotionsRoute = request()->routeIs('admin.promotions');
    $isChatInboxRoute = request()->routeIs('admin.chat-inbox');
    $isNotificationsRoute = request()->routeIs('admin.notifications');
    $isAnalyticsRoute = request()->routeIs('admin.analytics');
    $isReportsRoute = request()->routeIs('admin.reports');
    $isAccountRoute = request()->routeIs('admin.account');
@endphp

<aside class="border-b border-love-pink-100/80 bg-white/90 shadow-[18px_0_45px_-38px_rgba(83,35,57,0.38)] backdrop-blur-xl lg:sticky lg:top-0 lg:h-screen lg:border-b-0 lg:border-r">
    <div class="flex h-full flex-col">
        <div class="border-b border-love-pink-100 px-5 py-6">
            <a class="flex items-center gap-4" href="{{ route('admin.dashboard') }}" aria-label="Go to Loveby_Ade admin dashboard">
                <span class="flex h-[3.25rem] w-[3.25rem] items-center justify-center overflow-hidden rounded-full border border-white bg-white p-px shadow-[0_10px_24px_-18px_rgba(236,72,153,0.55)] ring-1 ring-white">
                    <img class="h-full w-full rounded-full object-cover" src="{{ asset('images/lovebyadelogo.png') }}" alt="Loveby_Ade logo">
                </span>
                <span class="min-w-0">
                    <span class="block truncate font-display text-xl font-bold leading-none text-[#3b1728]">Loveby_Ade</span>
                    <span class="mt-1 block truncate text-sm font-medium text-[#9a6c7b]">Sweet Admin</span>
                </span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-6">
            <div>
                <p class="px-2 text-sm font-semibold text-[#9a6c7b]">Workspace</p>
                <div class="mt-3 grid gap-1.5">
                    <x-admin.sidebar-link :href="route('admin.dashboard')" label="Dashboard" icon="grid" :active="$isDashboardRoute" />
                    <x-admin.sidebar-link :href="route('admin.orders')" label="Orders" icon="bag" :active="$isOrdersRoute" />
                    <x-admin.sidebar-link :href="route('admin.products')" label="Products" icon="cookie" :active="$isProductsRoute" />
                    <x-admin.sidebar-link :href="route('admin.customers')" label="Customers" icon="users" :active="$isCustomersRoute" />
                </div>
            </div>

            <div class="mt-9">
                <p class="px-2 text-sm font-semibold text-[#9a6c7b]">Engage</p>
                <div class="mt-3 grid gap-1.5">
                    <x-admin.sidebar-link :href="route('admin.promotions')" label="Promotions" icon="tag" :active="$isPromotionsRoute" />
                    <x-admin.sidebar-link :href="route('admin.chat-inbox')" label="Chat Inbox" icon="chat" :active="$isChatInboxRoute" />
                    <x-admin.sidebar-link :href="route('admin.notifications')" label="Notifications" icon="bell" :active="$isNotificationsRoute" badge="3" />
                </div>
            </div>

            <div class="mt-9">
                <p class="px-2 text-sm font-semibold text-[#9a6c7b]">Insights</p>
                <div class="mt-3 grid gap-1.5">
                    <x-admin.sidebar-link :href="route('admin.analytics')" label="Analytics" icon="chart" :active="$isAnalyticsRoute" />
                    <x-admin.sidebar-link :href="route('admin.reports')" label="Reports" icon="report" :active="$isReportsRoute" />
                    <x-admin.sidebar-link :href="route('admin.account')" label="Account" icon="settings" :active="$isAccountRoute" />
                </div>
            </div>
        </nav>

        <div class="border-t border-love-pink-100 px-5 py-5">
            <div class="flex items-center gap-4">
                <x-admin.profile-avatar class="h-12 w-12 text-sm" />
                <span class="min-w-0">
                    <span class="block truncate text-base font-extrabold text-[#512438]">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    <span class="mt-0.5 block truncate text-xs font-medium text-[#9a6c7b]">Owner - Loveby_Ade</span>
                </span>
            </div>
        </div>
    </div>
</aside>
