<footer id="contact" class="mt-20 border-t border-white/70 bg-white/70 backdrop-blur-xl">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.2fr_0.8fr_0.8fr] lg:px-8">
        <div>
            <x-brand-mark :href="route('home')" />
            <p class="mt-4 max-w-md text-sm leading-7 text-slate-500">
                Loveby_Ade is your soft, modern dessert storefront for cakes, pastries, cookies, and sweet daily cravings.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Quick Links</h3>
            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-600">
                <a class="transition hover:text-love-pink-500" href="{{ route('home') }}#products">Products</a>
                <a class="transition hover:text-love-pink-500" href="{{ route('home') }}#recommended">Recommended</a>
                <a class="transition hover:text-love-pink-500" href="{{ route('home') }}#about">About Us</a>
                <a class="transition hover:text-love-pink-500" href="{{ route('contact') }}">Contact Admin</a>
                <a class="transition hover:text-love-pink-500" href="{{ route('login') }}">Login</a>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Contact</h3>
            <div class="mt-4 space-y-3 text-sm text-slate-600">
                <a class="block transition hover:text-love-pink-500" href="mailto:hello@lovebyade.test">hello@lovebyade.test</a>
                <p>+63 912 345 6789</p>
                <p>Open daily for fresh bakes and gift boxes.</p>
            </div>
        </div>
    </div>
</footer>
