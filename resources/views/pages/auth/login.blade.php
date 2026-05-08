    @extends('layouts.guest')

    @section('title', 'Loveby_Ade | Login')
    @section('description', 'Sign in to your Loveby_Ade account to continue shopping for cakes, pastries, donuts, and cookies.')
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
                title="Welcome back, let's pick up your treats."
                description="Sign in to continue with your desserts, saved orders, and favorites.">

                {{-- Login form --}}
                <form class="mt-5 space-y-3.5">
                    @csrf
                    <x-auth.form-field
                        id="login-email"
                        name="email"
                        label="Email"
                        type="email"
                        placeholder="you@example.com"
                        autocomplete="email"
                        icon="email"/>

                    <x-auth.form-field
                        id="login-password"
                        name="password"
                        label="Password"
                        type="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        icon="password"/>

                    <div class="flex items-center justify-between gap-4 pt-0.5 text-sm">
                        <label class="inline-flex items-center gap-3 font-medium text-slate-600" for="remember">
                            <input class="h-4 w-4 rounded border-slate-300 text-love-pink-500 focus:ring-love-pink-200" id="remember" name="remember" type="checkbox">
                            <span>Remember me</span>
                        </label>
                        <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    </div>

                    <a class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('login.otp') }}">
                        Login
                    </a>
                </form>

                <div class="my-4 flex items-center gap-4">
                    <span class="h-px flex-1 bg-slate-200"></span>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">or</span>
                    <span class="h-px flex-1 bg-slate-200"></span>
                </div>

                <button class="inline-flex w-full items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-love-blue-200 hover:text-love-blue-500" type="button">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-.9 2.4-1.9 3.2l3.1 2.4c1.8-1.7 2.8-4.1 2.8-6.9 0-.7-.1-1.4-.2-2H12Z"/>
                        <path fill="#34A853" d="M12 21c2.7 0 4.9-.9 6.6-2.4l-3.1-2.4c-.9.6-2 1-3.5 1-2.7 0-4.9-1.8-5.7-4.2H3.1v2.5A10 10 0 0 0 12 21Z"/>
                        <path fill="#4A90E2" d="M6.3 13c-.2-.6-.3-1.3-.3-2s.1-1.4.3-2V6.5H3.1A10 10 0 0 0 2 11c0 1.6.4 3.1 1.1 4.5L6.3 13Z"/>
                        <path fill="#FBBC05" d="M12 4.8c1.5 0 2.8.5 3.9 1.5l2.9-2.9C16.9 1.7 14.7 1 12 1A10 10 0 0 0 3.1 6.5L6.3 9c.8-2.5 3-4.2 5.7-4.2Z"/>
                    </svg>
                    <span>Login with Google</span>
                </button>

                <p class="mt-4 text-center text-sm text-slate-500">
                    Don't have an account?
                    <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('register') }}">
                        Sign up
                    </a>
                </p>
            </x-auth.page-card>
        </main>
    @endsection
