@extends('layouts.guest')

@section('title', 'Loveby_Ade | Forgot Password')
@section('description', 'Request a Loveby_Ade password recovery code.')
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
            title="Forgot your password?"
            description="Enter your email and we will send a 6-digit verification code to help you recover your account."
        >
            <form class="mt-5 space-y-4">
                @csrf
                <x-auth.form-field
                    id="forgot-password-email"
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    icon="email"
                />

                <a class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('password.otp') }}">
                    Send OTP
                </a>
            </form>

            <p class="mt-4 text-center text-sm text-slate-500">
                Remembered your password?
                <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('login') }}">
                    Back to login
                </a>
            </p>
        </x-auth.page-card>
    </main>
@endsection
