@extends('layouts.admin')

@section('title', 'Account | Loveby_Ade Admin')
@section('description', 'Manage the admin profile, contact information, and account access.')

@section('content')
    @php
        $user = $user ?? auth()->user();
        $profileNote = 'Sweet Admin managing Loveby_Ade orders, products, customers, and reports.';
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

    <div class="min-h-screen bg-[linear-gradient(180deg,#fff8fb_0%,#fff1f6_46%,#fffaf7_100%)]">
        <header class="sticky top-0 z-20 border-b border-love-pink-100/80 bg-white/82 backdrop-blur-xl">
            <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#512438] lg:flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6.75 19.25a5.25 5.25 0 0 1 10.5 0" /></svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-[#3b1728]">Account</h1>
                        <p class="mt-1 truncate text-sm font-medium text-[#9a6c7b]">Manage your admin profile and access.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative min-w-0 flex-1 sm:w-96 sm:flex-none" for="admin-account-search">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6.5" /><path stroke-linecap="round" d="m16 16 4.5 4.5" /></svg>
                        </span>
                        <input class="h-12 w-full rounded-full border border-love-pink-100 bg-white/88 px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-account-search" type="search" placeholder="Search orders, products, customers...">
                    </label>

                    <button class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="View notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.5a5.25 5.25 0 1 1 10.5 0c0 5.25 2.25 6.75 2.25 6.75H4.5s2.25-1.5 2.25-6.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M10 19.5a2 2 0 0 0 4 0" /></svg>
                        <span class="absolute -right-0.5 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-love-blue-300 px-1 text-xs font-extrabold text-[#512438]">3</span>
                    </button>

                    <div class="flex h-12 shrink-0 items-center gap-3 rounded-full border border-love-pink-100 bg-white/88 py-1 pl-1 pr-4 shadow-[0_18px_35px_-28px_rgba(81,36,56,0.35)]">
                        <x-admin.profile-avatar :user="$user" class="h-10 w-10 text-sm" />
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-extrabold leading-tight text-[#512438]">{{ $user->name }}</span>
                            <span class="block text-xs font-medium leading-tight text-[#9a6c7b]">Admin</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-7 sm:px-6 lg:px-10" data-admin-account>
            @if (session('status'))
                <div class="mb-5 rounded-[1rem] border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-extrabold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form class="grid gap-6 xl:grid-cols-[22rem_minmax(0,1fr)]" action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                    <div class="flex flex-col items-center text-center">
                        <x-admin.profile-avatar :user="$user" class="h-24 w-24 text-2xl shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)]" />
                        <h2 class="mt-4 text-2xl font-extrabold text-[#3b1728]">{{ $user->name }}</h2>
                        <p class="mt-1 text-sm font-semibold text-[#9a6c7b]">Owner - Loveby_Ade</p>
                    </div>

                    <label class="mt-6 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-[1rem] border border-dashed border-love-pink-200 bg-love-cream px-4 py-5 text-center text-sm font-extrabold text-[#512438] transition hover:border-love-pink-300 hover:bg-love-pink-100/60" for="admin-profile-photo">
                        <svg class="h-6 w-6 text-love-pink-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5.75 18.25h12.5V8.75l-3-3h-9.5v12.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.25 14.25 11 12.5l1.25 1.25 2.5-3 2.25 3.5" /></svg>
                        Change profile photo
                        <input class="sr-only" id="admin-profile-photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" data-profile-photo-input>
                    </label>
                    @error('profile_photo')
                        <p class="mt-3 text-center text-sm font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </section>

                <section class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#3b1728]">Profile details</h2>
                        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Update the information shown across the admin workspace.</p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="admin-account-name">Name<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-account-name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>@error('name')<span class="text-xs font-semibold text-red-500">{{ $message }}</span>@enderror</label>
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="admin-account-role">Role<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-account-role" type="text" value="Owner - Loveby_Ade" readonly></label>
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="admin-account-email">Email<input class="h-12 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-account-email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>@error('email')<span class="text-xs font-semibold text-red-500">{{ $message }}</span>@enderror</label>
                        <label class="grid gap-2 text-sm font-extrabold text-[#512438]" for="admin-account-phone">
                            Phone
                            <span class="flex h-12 items-center rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-semibold text-[#512438] transition focus-within:border-love-pink-300 focus-within:bg-white focus-within:ring-4 focus-within:ring-love-pink-100/80">
                                <span class="shrink-0 text-[#512438]">+63-</span>
                                <input class="min-w-0 flex-1 bg-transparent text-sm font-semibold text-[#512438] outline-none placeholder:text-[#d3a5b5]" id="admin-account-phone" name="contact_number_digits" type="tel" value="{{ old('contact_number_digits', $phoneDigits($user->contact_number)) }}" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="tel-national" placeholder="0000000000" data-phone-digits>
                            </span>
                            @if ($errors->has('contact_number_digits') || $errors->has('contact_number'))
                                <span class="text-xs font-semibold text-red-500">{{ $errors->first('contact_number_digits') ?: $errors->first('contact_number') }}</span>
                            @endif
                        </label>
                    </div>

                    <label class="mt-5 grid gap-2 text-sm font-extrabold text-[#512438]" for="admin-account-bio">Profile note<textarea class="min-h-32 resize-y rounded-[1rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-semibold leading-6 text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-account-bio" name="address">{{ old('address', $user->address ?: $profileNote) }}</textarea>@error('address')<span class="text-xs font-semibold text-red-500">{{ $message }}</span>@enderror</label>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 bg-white px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="reset">Reset</button>
                        <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_18px_35px_-24px_rgba(236,72,153,0.75)] transition hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">Save changes</button>
                    </div>
                </section>
            </form>
        </main>
    </div>
@endsection
