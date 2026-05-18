<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReviewReplyRequest;
use App\Http\Requests\StoreProductReviewRequest;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use App\Models\User;
use App\Services\ProductReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function __construct(private ProductReviewService $reviews) {}

    public function store(StoreProductReviewRequest $request, string $slug): RedirectResponse
    {
        $this->reviews->store($slug, $request->user(), $request->validated(), $request->file('media', []));
        $productUrl = $slug === 'pastel-donut-box'
            ? route('products.show')
            : route('products.show-by-slug', $slug);

        return redirect()
            ->to($productUrl.'#reviews')
            ->with('status', 'Thanks for sharing your review.');
    }

    public function like(Request $request, ProductReview $review): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $liked = $this->reviews->toggleLike($review, $user);

        return redirect()
            ->to($this->productUrl($review).'#review-'.$review->id)
            ->with('status', $liked ? 'Review liked.' : 'Review like removed.');
    }

    public function destroy(Request $request, ProductReview $review): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User && $review->user_id === $user->id, 403);

        $productUrl = $this->productUrl($review);
        $this->reviews->destroy($review);

        return redirect()
            ->to($productUrl.'#reviews')
            ->with('status', 'Review deleted.');
    }

    public function reply(StoreProductReviewReplyRequest $request, ProductReview $review): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $this->reviews->reply($review, $user, $request->validated());

        return redirect()
            ->to($this->productUrl($review).'#review-'.$review->id)
            ->with('status', 'Reply posted.');
    }

    public function destroyReply(Request $request, ProductReviewReply $reply): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User && $reply->user_id === $user->id, 403);

        $reply->loadMissing('review.product');
        $review = $reply->review;
        $reply->delete();

        return redirect()
            ->to($this->productUrl($review).'#review-'.$review->id)
            ->with('status', 'Reply deleted.');
    }

    private function productUrl(ProductReview $review): string
    {
        $review->loadMissing('product');
        $slug = $review->product?->slug;

        if ($slug === 'pastel-donut-box') {
            return route('products.show');
        }

        return route('products.show-by-slug', $slug);
    }
}
