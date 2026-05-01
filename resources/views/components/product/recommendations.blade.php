<section class="mx-auto mt-12 max-w-[86rem] px-4 sm:px-6 lg:px-8">
    <x-home.section-heading
        title="You may also like"
        description="Recommended sweet picks based on the products customers often view together."
        action-label="Back to collection"
        :action-href="route('home').'#recommended'"
    />

    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <x-home.product-card image="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=900&q=80" title="Chocolate Chip Cookies" price="PHP 90" sold="226 sold" stock-left="21 left" rating="4.7" />
        <x-home.product-card image="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=900&q=80" title="Mini Cake Cups" price="PHP 150" sold="142 sold" stock-left="9 left" rating="4.9" />
        <x-home.product-card image="https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?auto=format&fit=crop&w=900&q=80" title="Strawberry Tartlets" price="PHP 110" sold="154 sold" stock-left="13 left" rating="4.8" />
        <x-home.product-card image="https://images.unsplash.com/photo-1515037893149-de7f840978e2?auto=format&fit=crop&w=900&q=80" title="Milk Tea Cookie Tin" price="PHP 80" sold="205 sold" stock-left="16 left" rating="4.5" />
    </div>
</section>
