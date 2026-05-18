@extends('layouts.admin')

@section('title', 'Audit Logs | Loveby_Ade Admin')
@section('description', 'Review important user and admin activity.')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4-1.8 6.25-4.75 6.25-8.5v-5.5L12 3.75l-6.25 2.5v5.5c0 3.75 2.25 6.7 6.25 8.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.25 12.25 1.75 1.75 3.75-4" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Audit Logs</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Review important user and admin activity.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <form class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" method="GET" action="{{ route('admin.audit-logs') }}">
                        <label for="admin-audit-search">
                            <span class="sr-only">Search audit logs</span>
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6.5" />
                                    <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                                </svg>
                            </span>
                            <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-audit-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search logs...">
                        </label>
                    </form>

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

        <main class="px-4 py-7 sm:px-6 lg:px-10" data-admin-audit-logs>
            <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                <div class="flex flex-col gap-4 border-b border-love-pink-100/80 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <h2 class="truncate text-xl font-extrabold text-[#3b1728]">Activity History</h2>
                        <p class="mt-1 text-sm font-medium text-[#9a6c7b]">{{ number_format($logs->total()) }} important {{ str('event')->plural($logs->total()) }} recorded</p>
                    </div>

                    <form class="flex flex-col gap-3 sm:flex-row sm:items-center" method="GET" action="{{ route('admin.audit-logs') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="page_size" value="{{ request('page_size', 5) }}">

                        <label class="min-w-0 sm:w-44" for="audit-module-filter">
                            <span class="sr-only">Module</span>
                            <select class="h-10 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="audit-module-filter" name="module" onchange="this.form.submit()">
                                <option value="">All modules</option>
                                @foreach ($modules as $module)
                                    <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="min-w-0 sm:w-44" for="audit-status-filter">
                            <span class="sr-only">Status</span>
                            <select class="h-10 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="audit-status-filter" name="status" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </label>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table id="admin-audit-logs-table" class="w-full min-w-[66rem] border-separate border-spacing-y-3 px-4 text-left text-sm">
                        <thead>
                            <tr class="text-xs font-extrabold uppercase tracking-wide text-[#9a6c7b]">
                                <th class="px-3 py-2">Date &amp; Time</th>
                                <th class="px-3 py-2">User</th>
                                <th class="px-3 py-2">Activity</th>
                                <th class="px-3 py-2">Module</th>
                                <th class="px-3 py-2">Description</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                @php
                                    $displayUser = $log->user;
                                    $userName = $log->user_name ?? $displayUser?->name ?? 'Guest';
                                    $userEmail = $log->user_email ?? $displayUser?->email;
                                    $profilePhotoUrl = $displayUser?->profilePhotoUrl();
                                    $userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
                                    $statusClass = match ($log->status) {
                                        'failed' => 'bg-rose-50 text-rose-600 ring-rose-100',
                                        'warning' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                        default => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                                    };
                                @endphp
                                <tr class="bg-white shadow-[0_16px_38px_-32px_rgba(81,36,56,0.45)]">
                                    <td class="rounded-l-[1rem] border-y border-l border-love-pink-100/70 px-3 py-4 font-semibold text-[#512438]">
                                        {{ $log->created_at?->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="border-y border-love-pink-100/70 px-3 py-4">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full border border-love-pink-100 bg-love-cream text-sm font-extrabold text-love-pink-500">
                                                @if ($profilePhotoUrl)
                                                    <img class="h-full w-full object-cover" src="{{ $profilePhotoUrl }}" alt="{{ $userName }} profile photo">
                                                @else
                                                    {{ $userInitial }}
                                                @endif
                                            </span>
                                            <span class="min-w-0">
                                                <span class="block truncate font-extrabold text-[#3b1728]">{{ $userName }}</span>
                                                <span class="mt-0.5 block truncate text-xs font-semibold text-[#9a6c7b]">{{ $userEmail ?? 'No email recorded' }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="border-y border-love-pink-100/70 px-3 py-4 font-semibold text-[#512438]">{{ $log->activity }}</td>
                                    <td class="border-y border-love-pink-100/70 px-3 py-4">
                                        <span class="inline-flex h-8 items-center rounded-full border border-love-pink-100 bg-love-cream px-3 text-xs font-extrabold text-[#512438]">{{ $log->module }}</span>
                                    </td>
                                    <td class="border-y border-love-pink-100/70 px-3 py-4 font-medium text-[#6f4354]">{{ $log->description }}</td>
                                    <td class="rounded-r-[1rem] border-y border-r border-love-pink-100/70 px-3 py-4">
                                        <span class="inline-flex h-8 items-center rounded-full px-3 text-xs font-extrabold ring-1 {{ $statusClass }}">{{ str($log->status)->headline() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-3 py-8 text-center text-base font-semibold text-[#9a6c7b]" colspan="6">No audit logs match this view.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <nav class="flex flex-col gap-4 border-t border-love-pink-100 px-5 py-4 xl:flex-row xl:items-center xl:justify-between" aria-label="Audit log pagination">
                    <form class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5" method="GET" action="{{ route('admin.audit-logs') }}">
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request()->filled('module'))
                            <input type="hidden" name="module" value="{{ request('module') }}">
                        @endif
                        @if (request()->filled('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif

                        <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-audit-page-size">
                            <span>Rows per page</span>
                            <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-audit-page-size" name="page_size" aria-controls="admin-audit-logs-table" onchange="this.form.submit()">
                                @foreach ([5, 10, 20] as $size)
                                    <option value="{{ $size }}" @selected((int) request('page_size', 5) === $size)>{{ $size }} rows</option>
                                @endforeach
                            </select>
                        </label>

                        <p class="text-sm font-medium text-[#9a6c7b]">
                            Showing {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} of {{ number_format($logs->total()) }} logs
                        </p>
                    </form>

                    <div class="flex flex-wrap items-center gap-2">
                        @if ($logs->hasPages())
                            @php
                                $startPage = max(1, $logs->currentPage() - 2);
                                $endPage = min($logs->lastPage(), $logs->currentPage() + 2);
                            @endphp

                            <div class="flex flex-wrap items-center gap-2">
                                @if ($logs->onFirstPage())
                                    <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Previous</span>
                                @else
                                    <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $logs->previousPageUrl() }}">Previous</a>
                                @endif

                                @foreach ($logs->getUrlRange($startPage, $endPage) as $page => $url)
                                    @if ($page === $logs->currentPage())
                                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-love-pink-400 px-4 text-sm font-extrabold text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]" aria-current="page">{{ $page }}</span>
                                    @else
                                        <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $url }}">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($logs->hasMorePages())
                                    <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $logs->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Next</span>
                                @endif
                            </div>
                        @else
                            <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Previous</span>
                            <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-love-pink-400 px-4 text-sm font-extrabold text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]" aria-current="page">1</span>
                            <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Next</span>
                        @endif
                    </div>
                </nav>
            </section>
        </main>
    </div>
@endsection
