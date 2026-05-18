<?php

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewLike;
use App\Models\ProductReviewReply;
use App\Models\User;

test('authenticated users can submit an anonymous product review', function () {
    $user = User::factory()->create([
        'name' => 'Jericho Salvador',
    ]);
    $product = Product::factory()->create([
        'slug' => 'chocolate-tiramisu',
        'rating' => 0,
    ]);

    $this->actingAs($user)
        ->post(route('products.reviews.store', $product->slug), [
            'display_name' => 'Jericho Salvador',
            'rating' => 4,
            'review' => 'The tiramisu was smooth, fresh, and packed nicely for delivery.',
            'is_anonymous' => '1',
        ])
        ->assertRedirect(route('products.show-by-slug', $product->slug).'#reviews');

    $review = ProductReview::query()->first();

    expect($review)->not->toBeNull()
        ->and($review->product_id)->toBe($product->id)
        ->and($review->user_id)->toBe($user->id)
        ->and($review->rating)->toBe(4)
        ->and($review->is_anonymous)->toBeTrue()
        ->and($review->displayName())->toBe('J**o S**r')
        ->and($review->media_paths)->toBe([]);

    expect((float) $product->refresh()->rating)->toBe(4.0);
});

test('product page renders stored reviews instead of mock reviews', function () {
    $product = Product::factory()->create([
        'slug' => 'berry-cake',
        'title' => 'Berry Cake',
    ]);
    $user = User::factory()->create([
        'name' => 'Jericho Salvador',
    ]);
    $replyAuthor = User::factory()->create([
        'name' => 'Ade Support',
    ]);

    $review = ProductReview::factory()
        ->for($product)
        ->for($user)
        ->anonymous()
        ->create([
            'author_name' => 'Jericho Salvador',
            'rating' => 5,
            'body' => 'Fresh, pretty, and not too sweet. The box arrived clean.',
        ]);
    ProductReviewLike::factory()->for($review, 'review')->create();
    ProductReviewReply::factory()
        ->for($review, 'review')
        ->for($replyAuthor)
        ->create([
            'body' => 'Thank you for leaving such a sweet review.',
            'created_at' => '2026-05-18 14:05:00',
        ]);

    $this->get(route('products.show-by-slug', $product->slug))
        ->assertSuccessful()
        ->assertSee('Berry Cake')
        ->assertSee('J**o S**r')
        ->assertSee('Fresh, pretty, and not too sweet. The box arrived clean.')
        ->assertSee('Thank you for leaving such a sweet review.')
        ->assertSee('2026-05-18 2:05 PM')
        ->assertDontSee('14:05')
        ->assertSee('data-review-filter="all"', false)
        ->assertSee('data-review-filter="rating:5"', false)
        ->assertSee('data-review-filter="comments"', false)
        ->assertSee('data-review-filter="media"', false)
        ->assertSee('5 Star (1)')
        ->assertDontSee('Sobrang ganda ng packaging')
        ->assertDontSee('No reviews yet');
});

test('authenticated users can submit multiple reviews for the same product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'slug' => 'repeat-review-cake',
        'rating' => 0,
    ]);

    foreach ([5, 3] as $rating) {
        $this->actingAs($user)
            ->post(route('products.reviews.store', $product->slug), [
                'rating' => $rating,
                'review' => 'This product deserves another note because each order felt different.',
            ])
            ->assertRedirect(route('products.show-by-slug', $product->slug).'#reviews');
    }

    expect(ProductReview::query()->whereBelongsTo($product)->whereBelongsTo($user)->count())->toBe(2)
        ->and((float) $product->refresh()->rating)->toBe(4.0);
});

test('authenticated users can like and unlike a product review', function () {
    $user = User::factory()->create();
    $review = ProductReview::factory()->create();

    $this->actingAs($user)
        ->post(route('products.reviews.likes.toggle', $review))
        ->assertRedirect(route('products.show-by-slug', $review->product->slug).'#review-'.$review->id);

    expect(ProductReviewLike::query()->whereBelongsTo($review, 'review')->whereBelongsTo($user)->count())->toBe(1);

    $this->actingAs($user)
        ->post(route('products.reviews.likes.toggle', $review))
        ->assertRedirect(route('products.show-by-slug', $review->product->slug).'#review-'.$review->id);

    expect(ProductReviewLike::query()->whereBelongsTo($review, 'review')->whereBelongsTo($user)->count())->toBe(0);
});

test('authenticated users can delete their own product reviews', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'rating' => 3,
    ]);
    $review = ProductReview::factory()
        ->for($product)
        ->for($user)
        ->create([
            'rating' => 1,
        ]);
    ProductReview::factory()
        ->for($product)
        ->create([
            'rating' => 5,
        ]);
    ProductReviewLike::factory()->for($review, 'review')->create();
    ProductReviewReply::factory()->for($review, 'review')->create();

    $this->actingAs($user)
        ->delete(route('products.reviews.destroy', $review))
        ->assertRedirect(route('products.show-by-slug', $product->slug).'#reviews');

    expect(ProductReview::query()->whereKey($review)->exists())->toBeFalse()
        ->and(ProductReviewLike::query()->whereBelongsTo($review, 'review')->exists())->toBeFalse()
        ->and(ProductReviewReply::query()->whereBelongsTo($review, 'review')->exists())->toBeFalse()
        ->and((float) $product->refresh()->rating)->toBe(5.0);
});

test('authenticated users cannot delete another users product review', function () {
    $user = User::factory()->create();
    $review = ProductReview::factory()->create();

    $this->actingAs($user)
        ->delete(route('products.reviews.destroy', $review))
        ->assertForbidden();

    expect(ProductReview::query()->whereKey($review)->exists())->toBeTrue();
});

test('authenticated users can reply to product reviews', function () {
    $user = User::factory()->create();
    $review = ProductReview::factory()->create();

    $this->actingAs($user)
        ->post(route('products.reviews.replies.store', $review), [
            'reply' => 'I agree with this review and had the same smooth delivery experience.',
        ])
        ->assertRedirect(route('products.show-by-slug', $review->product->slug).'#review-'.$review->id);

    expect(ProductReviewReply::query()->whereBelongsTo($review, 'review')->whereBelongsTo($user)->first())
        ->body->toBe('I agree with this review and had the same smooth delivery experience.');
});

test('authenticated users can delete their own product review replies', function () {
    $user = User::factory()->create();
    $reply = ProductReviewReply::factory()
        ->for($user)
        ->create();
    $review = $reply->review;

    $this->actingAs($user)
        ->delete(route('products.reviews.replies.destroy', $reply))
        ->assertRedirect(route('products.show-by-slug', $review->product->slug).'#review-'.$review->id);

    expect(ProductReviewReply::query()->whereKey($reply)->exists())->toBeFalse();
});

test('authenticated users cannot delete another users product review reply', function () {
    $user = User::factory()->create();
    $reply = ProductReviewReply::factory()->create();

    $this->actingAs($user)
        ->delete(route('products.reviews.replies.destroy', $reply))
        ->assertForbidden();

    expect(ProductReviewReply::query()->whereKey($reply)->exists())->toBeTrue();
});

test('guests are sent to login before submitting reviews', function () {
    $product = Product::factory()->create([
        'slug' => 'guest-review-cake',
    ]);

    $this->post(route('products.reviews.store', $product->slug), [
        'rating' => 5,
        'review' => 'This should require a signed in customer before it is saved.',
    ])
        ->assertRedirect(route('login'));

    expect(ProductReview::query()->count())->toBe(0);
});
