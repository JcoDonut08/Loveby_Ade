@props([
    'products',
])

<section class="mx-auto mt-12 max-w-[86rem] px-4 sm:px-6 lg:px-8">
    <x-home.section-heading
        title="You may also like"
        description="Recommended sweet picks based on the products customers often view together."
        action-label="Back to collection"
        :action-href="route('products.index')"
    />

    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @foreach ($products as $product)
            <x-home.product-card
                :image="$product['image']"
                :title="$product['title']"
                :price="$product['price']"
                :sold="$product['sold_label']"
                :stock-left="$product['stock_label']"
                :rating="$product['rating']"
                :href="$product['show_url']"
                :slug="$product['slug']"
            />
        @endforeach
    </div>
</section>
