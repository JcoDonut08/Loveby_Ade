@extends('layouts.admin')

@section('title', 'Reports | Loveby_Ade Admin')
@section('description', 'Export and share business reports.')

@php
    $reportCards = [
        [
            'key' => 'sales',
            'title' => 'Sales report',
            'subtitle' => 'Revenue, orders and AOV across periods',
            'tone' => 'bg-love-pink-400',
            'icon' => 'money',
        ],
        [
            'key' => 'products',
            'title' => 'Product performance',
            'subtitle' => 'Top-selling desserts and stock turnover',
            'tone' => 'bg-love-orange-400',
            'icon' => 'product',
        ],
        [
            'key' => 'audit-logs',
            'title' => 'Audit logs report',
            'subtitle' => 'User activity, admin actions and security events',
            'tone' => 'bg-love-blue-500',
            'icon' => 'audit',
        ],
    ];

@endphp

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <form id="admin-report-filter-form" method="GET" action="{{ route('admin.reports') }}"></form>

        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h8.5l2 2v12.5H6.75V4.75Z" /><path stroke-linecap="round" d="M10 14.5v2M12.5 12.5v4M15 10.5v6" /></svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Reports</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Export and share business reports.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-report-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6.5" /><path stroke-linecap="round" d="m16 16 4.5 4.5" /></svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-report-search" form="admin-report-filter-form" name="search" type="search" value="{{ $filters['search'] }}" placeholder="Search reports, customers, products, activity...">
                    </label>

                    <x-admin.notification-link />

                    <div class="flex h-12 shrink-0 items-center gap-3 rounded-full border border-love-pink-100 bg-white/88 py-1 pl-1 pr-4 shadow-[0_18px_35px_-28px_rgba(81,36,56,0.35)]">
                        <x-admin.profile-avatar class="h-10 w-10 text-sm" />
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-extrabold leading-tight text-[#512438]">{{ auth()->user()?->name ?? 'Admin' }}</span>
                            <span class="block text-xs font-medium leading-tight text-[#9a6c7b]">Admin</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-7 sm:px-6 lg:px-10" data-admin-reports>
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Generate reports</h2>
                        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Pick a date range, generate a preview, then download the file.</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="report-from-date">From<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="report-from-date" form="admin-report-filter-form" name="from" type="date" value="{{ $filters['from'] }}"></label>
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="report-to-date">To<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="report-to-date" form="admin-report-filter-form" name="to" type="date" value="{{ $filters['to'] }}"></label>
                    </div>
                </div>
            </section>

            <section class="mt-6 grid gap-6 xl:grid-cols-3">
                @foreach ($reportCards as $card)
                    @php
                        $isGenerated = $previewReport === $card['key'] && is_array($preview);
                        $selectedFormat = $isGenerated ? $previewFormat : '';
                    @endphp

                    <article class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                        <div class="flex items-start gap-4">
                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[1rem] text-white {{ $card['tone'] }}">
                                @switch($card['icon'])
                                    @case('money')
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M16.25 8.25H10.5a2.25 2.25 0 0 0 0 4.5h3a2.25 2.25 0 0 1 0 4.5H7.75" /></svg>
                                        @break

                                    @case('product')
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13.2A7.9 7.9 0 1 1 10.8 4 4 4 0 0 0 15 8.2 4 4 0 0 0 20 13.2Z" /><path stroke-linecap="round" d="M8.5 11h.01M12 15h.01M8.25 16.5h.01" /></svg>
                                        @break

                                    @default
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.75h10.5v14.5H6.75z" /><path stroke-linecap="round" d="M9.25 8h5.5M9.25 11.5h5.5M9.25 15h3.5" /></svg>
                                @endswitch
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-xl font-extrabold text-[#3b1728]">{{ $card['title'] }}</h3>
                                <p class="mt-2 text-sm font-medium text-[#9a6c7b]">{{ $card['subtitle'] }}</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4">
                            <div class="flex flex-wrap gap-2">
                                <input form="admin-report-filter-form" name="{{ $card['key'] }}_format" type="hidden" value="{{ $selectedFormat }}" data-report-format-input="{{ $card['key'] }}">
                                <button class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-pressed="false" data-report-format-button data-report-format-group="{{ $card['key'] }}" data-report-format-value="pdf">PDF</button>
                                <button class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-pressed="false" data-report-format-button data-report-format-group="{{ $card['key'] }}" data-report-format-value="excel">Excel</button>
                            </div>

                            <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" form="admin-report-filter-form" name="preview" value="{{ $card['key'] }}" type="submit" data-report-generate="{{ $card['key'] }}">Generate</button>
                        </div>

                    </article>
                @endforeach
            </section>

            @if (is_array($preview) && $previewReport !== null)
                @php
                    $previewFileUrl = route('admin.reports.export', array_filter([
                        'report' => $previewReport,
                        'preview' => 1,
                        'format' => $previewFormat,
                        'search' => $filters['search'],
                        'from' => $filters['from'],
                        'to' => $filters['to'],
                    ], fn ($value): bool => $value !== null && $value !== ''));
                @endphp

                <section class="mt-6 overflow-hidden rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                    <div class="grid gap-4 border-b border-love-pink-100 bg-love-cream px-6 py-5 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Generated file preview</p>
                            <h2 class="mt-1 text-2xl font-extrabold text-[#3b1728]">{{ $preview['title'] }}</h2>
                            <p class="mt-1 text-sm font-bold text-[#9a6c7b]">{{ count($preview['rows']) }} rows | {{ strtoupper($previewFormat === 'excel' ? 'xls' : $previewFormat) }} | {{ $preview['range_label'] }}</p>
                        </div>

                        <button class="inline-flex h-11 items-center justify-center rounded-full bg-[#3b1728] px-5 text-sm font-extrabold text-white transition hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" form="admin-report-filter-form" formaction="{{ route('admin.reports.export', ['report' => $previewReport]) }}" type="submit" data-report-download="{{ $previewReport }}">
                            Download {{ strtoupper($previewFormat === 'excel' ? 'XLS' : $previewFormat) }}
                        </button>
                    </div>

                    <div class="bg-white p-4">
                        <iframe class="h-[42rem] w-full rounded-xl border border-love-pink-100 bg-white" src="{{ $previewFileUrl }}" title="{{ $preview['title'] }} {{ strtoupper($previewFormat === 'excel' ? 'XLS' : $previewFormat) }} preview"></iframe>
                    </div>
                </section>
            @endif
        </main>
    </div>
@endsection
