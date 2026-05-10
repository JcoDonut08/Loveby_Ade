@extends('layouts.guest')

@section('title', 'Loveby_Ade | OTP Verification')
@section('description', 'Enter the 6-digit Loveby_Ade password recovery code.')
@section('body_classes', 'h-[100dvh] overflow-hidden bg-[radial-gradient(circle_at_top_left,#ffd9ea_0%,transparent_32%),radial-gradient(circle_at_bottom_right,#c9eeff_0%,transparent_30%),linear-gradient(180deg,#fff3f8_0%,#edf8ff_52%,#fff7f1_100%)] text-slate-900')

@section('content')
    <main class="relative flex h-[100dvh] items-center justify-center overflow-hidden px-4 py-6 sm:px-6">
        <div class="absolute left-[-4rem] top-8 h-56 w-56 rounded-full bg-love-pink-300/75 blur-3xl"></div>
        <div class="absolute bottom-6 right-[-3rem] h-56 w-56 rounded-full bg-love-blue-300/75 blur-3xl"></div>
        <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.6),transparent_68%)]"></div>

        <div class="absolute left-4 top-4 z-10 sm:left-6 sm:top-6">
            <div class="origin-left scale-90 sm:scale-100">
                <x-brand-mark :href="route('home')" />
            </div>
        </div>

        <x-auth.page-card
            title="Enter your 6-digit OTP."
            description="We sent a verification code to {{ $email ?? 'your email' }}. Type the code below to continue resetting your password."
        >
            @if (session('status'))
                <div class="mt-5 rounded-2xl border border-love-blue-200 bg-love-blue-100/80 px-4 py-3 text-sm font-semibold text-slate-700">
                    {{ session('status') }}
                </div>
            @endif

            <form class="mt-5 space-y-5" action="{{ route('password.otp.verify') }}" method="POST">
                @csrf

                <fieldset>
                    <legend class="text-center text-sm font-semibold text-slate-700">Verification code</legend>
                    <div class="mt-3 grid grid-cols-6 gap-2 sm:gap-3" data-otp-inputs>
                        <label class="sr-only" for="otp-1">Digit 1</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-1" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" autocomplete="one-time-code">
                        <label class="sr-only" for="otp-2">Digit 2</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-2" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]">
                        <label class="sr-only" for="otp-3">Digit 3</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-3" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]">
                        <label class="sr-only" for="otp-4">Digit 4</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-4" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]">
                        <label class="sr-only" for="otp-5">Digit 5</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-5" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]">
                        <label class="sr-only" for="otp-6">Digit 6</label>
                        <input class="h-12 rounded-2xl border border-slate-200 bg-white text-center text-lg font-extrabold text-slate-900 outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100 sm:h-14 sm:text-xl" id="otp-6" name="otp[]" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]">
                    </div>
                    @error('otp_code')
                        <p class="mt-2 text-center text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </fieldset>

                <button class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="submit">
                    Verify OTP
                </button>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-sm text-slate-500">
                <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('password.request') }}">
                    Change email
                </a>
                <span aria-hidden="true">-</span>
                <form action="{{ route('password.otp.resend') }}" method="POST">
                    @csrf
                    <button class="font-semibold text-love-blue-500 transition hover:text-love-blue-400" type="submit">
                    Resend code
                    </button>
                </form>
            </div>
        </x-auth.page-card>
    </main>
@endsection
