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
                @if (session('status'))
                    <div class="mt-5 rounded-2xl border border-love-blue-200 bg-love-blue-100/80 px-4 py-3 text-sm font-semibold text-slate-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="mt-5 space-y-3.5" action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <x-auth.form-field
                        id="login-email"
                        name="email"
                        label="Email"
                        type="email"
                        placeholder="you@example.com"
                        autocomplete="email"
                        icon="email"
                        required
                        autofocus/>

                    <x-auth.form-field
                        id="login-password"
                        name="password"
                        label="Password"
                        type="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        icon="password"
                        required/>

                    <div class="flex items-center justify-between gap-4 pt-0.5 text-sm">
                        <label class="inline-flex items-center gap-3 font-medium text-slate-600" for="remember">
                            <input class="h-4 w-4 rounded border-slate-300 text-love-pink-500 focus:ring-love-pink-200" id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                            <span>Remember me</span>
                        </label>
                        <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    </div>

                    <button class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.75)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="submit">
                        Login
                    </button>
                </form>

                <div class="my-4 flex items-center gap-4">
                    <span class="h-px flex-1 bg-slate-200"></span>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">or</span>
                    <span class="h-px flex-1 bg-slate-200"></span>
                </div>

                <x-auth.google-button label="Login with Google" />

                <p class="mt-4 text-center text-sm text-slate-500">
                    Don't have an account?
                    <a class="font-semibold text-love-pink-500 transition hover:text-love-pink-600" href="{{ route('register') }}">
                        Sign up
                    </a>
                </p>
            </x-auth.page-card>
        </main>
    @endsection
