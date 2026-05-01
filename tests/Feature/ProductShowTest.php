<?php

test('product overview page renders the storefront sections', function () {
    $this->get(route('products.show'))
        ->assertSuccessful()
        ->assertSee('Pastel Donut Box')
        ->assertSee('Add to cart')
        ->assertSee('Product Ratings')
        ->assertSee('Write a review')
        ->assertSee('You may also like')
        ->assertSee('data-review-rating', false)
        ->assertDontSee('Frontend preview only')
        ->assertDontSee('Verified');
});
