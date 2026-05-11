@extends('layouts.guest')

@section('title', 'Account | Loveby_Ade')
@section('description', 'View your Loveby_Ade account details.')
@section('body_classes', 'bg-[linear-gradient(180deg,#fff3f8_0%,#eff8ff_52%,#fff8f3_100%)] text-slate-900')

@section('content')
    <x-home.store-header />

    <main class="mx-auto min-h-[calc(100dvh-5.35rem)] max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-[2rem] border border-white/80 bg-white/92 shadow-[0_28px_70px_-42px_rgba(15,23,42,0.34)]">
            <div class="px-6 pt-6 sm:px-8 sm:pt-8">
                <span class="text-xs font-extrabold uppercase tracking-[0.28em] text-love-pink-500">Account</span>
                <h1 class="mt-3 font-display text-4xl text-slate-950">Your profile</h1>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <form class="mt-6 grid min-h-[32rem] border-t border-slate-100 lg:grid-cols-[18rem_1fr]" action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <aside class="flex flex-col items-center gap-5 border-b border-slate-100 bg-slate-50/70 px-6 py-8 text-center lg:border-b-0 lg:border-r">
                    <label class="group grid w-full cursor-pointer gap-4" for="profile_photo">
                        <span class="relative mx-auto flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-love-pink-100 text-4xl font-extrabold text-love-pink-500 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.55)] ring-1 ring-love-pink-100 transition group-hover:ring-love-pink-300">
                            <img class="{{ $profilePhotoUrl ? '' : 'hidden' }} h-full w-full object-cover" src="{{ $profilePhotoUrl ?? '' }}" alt="{{ $user->name }} profile photo" data-profile-photo-preview-image>
                            <span class="{{ $profilePhotoUrl ? 'hidden' : '' }}" data-profile-photo-preview-fallback>
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </span>
                        </span>
                        <span class="grid gap-1">
                            <span class="text-lg font-extrabold text-slate-950">{{ $user->name }}</span>
                            <span class="break-all text-sm font-medium text-slate-500">{{ $user->email }}</span>
                        </span>
                        <span class="inline-flex min-h-11 items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-[0_18px_40px_-28px_rgba(15,23,42,0.8)] transition group-hover:bg-love-pink-500">
                            Upload profile
                        </span>
                    </label>
                    <input class="sr-only" id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" data-profile-photo-input>
                    <p class="max-w-48 text-xs font-medium leading-5 text-slate-500">JPG, PNG, or WEBP up to 25 MB.</p>

                    @error('profile_photo')
                        <p class="text-sm font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </aside>

                <div class="grid content-start gap-5 px-6 py-8 sm:px-8 lg:py-10">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="grid gap-2 text-sm font-semibold text-slate-700" for="name">
                            Username
                            <input class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                            @error('name')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="grid gap-2 text-sm font-semibold text-slate-700" for="email">
                            Email
                            <input class="h-12 rounded-2xl border border-slate-200 bg-slate-100 px-4 text-sm font-medium text-slate-500 outline-none" id="email" type="email" value="{{ $user->email }}" autocomplete="email" disabled>
                        </label>
                    </div>

                    <label class="grid gap-2 text-sm font-semibold text-slate-700" for="contact_number">
                        Contact no.
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="contact_number" name="contact_number" type="tel" value="{{ old('contact_number', $user->contact_number) }}" autocomplete="tel" placeholder="+63 912 345 6789">
                        @error('contact_number')
                            <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="grid gap-2 text-sm font-semibold text-slate-700" for="address">
                        Address
                        <textarea class="min-h-32 resize-y rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" id="address" name="address" autocomplete="street-address" placeholder="Street, barangay, city, province">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="flex justify-end">
                        <button class="inline-flex min-h-12 items-center justify-center rounded-full bg-love-pink-500 px-6 py-3 text-sm font-extrabold text-white shadow-[0_18px_42px_-24px_rgba(236,72,153,0.75)] transition hover:-translate-y-0.5 hover:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <x-home.store-footer />
@endsection
