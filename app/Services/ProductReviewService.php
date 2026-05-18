<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewLike;
use App\Models\ProductReviewReply;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductReviewService
{
    /**
     * @return array{items: Collection<int, array<string, mixed>>, summary: array<string, mixed>, can_review: bool}
     */
    public function forProductSlug(string $slug, ?User $viewer = null): array
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->first();

        if (! $product instanceof Product) {
            return [
                'items' => collect(),
                'summary' => $this->emptySummary(),
                'can_review' => false,
            ];
        }

        $reviews = ProductReview::query()
            ->with([
                'user',
                'replies' => fn ($query) => $query->with('user')->oldest(),
            ])
            ->withCount('likes')
            ->when($viewer instanceof User, fn ($query) => $query->withExists([
                'likes as liked_by_current_user' => fn ($likeQuery) => $likeQuery->whereBelongsTo($viewer),
            ]))
            ->whereBelongsTo($product)
            ->latest()
            ->get();

        return [
            'items' => $reviews
                ->values()
                ->map(fn (ProductReview $review, int $index): array => $this->presentReview($review, $product, $index, $viewer)),
            'summary' => $this->summary($reviews),
            'can_review' => true,
        ];
    }

    /**
     * @param  array{display_name?: string|null, rating: int|string, review: string, is_anonymous?: bool|string|int|null}  $data
     * @param  array<int, UploadedFile>  $media
     */
    public function store(string $slug, ?User $user, array $data, array $media = []): ProductReview
    {
        abort_unless($user instanceof User, 403);

        $product = Product::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return DB::transaction(function () use ($product, $user, $data, $media): ProductReview {
            $review = ProductReview::query()->create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'author_name' => $this->authorName($data['display_name'] ?? null, $user),
                'rating' => (int) $data['rating'],
                'body' => $data['review'],
                'media_paths' => $this->storeMedia($media),
                'is_anonymous' => (bool) ($data['is_anonymous'] ?? false),
            ]);

            $this->refreshProductRating($product);

            return $review;
        });
    }

    public function toggleLike(ProductReview $review, User $user): bool
    {
        $existingLike = ProductReviewLike::query()
            ->whereBelongsTo($review, 'review')
            ->whereBelongsTo($user)
            ->first();

        if ($existingLike instanceof ProductReviewLike) {
            $existingLike->delete();

            return false;
        }

        ProductReviewLike::query()->create([
            'product_review_id' => $review->id,
            'user_id' => $user->id,
        ]);

        return true;
    }

    /**
     * @param  array{reply: string}  $data
     */
    public function reply(ProductReview $review, User $user, array $data): ProductReviewReply
    {
        return ProductReviewReply::query()->create([
            'product_review_id' => $review->id,
            'user_id' => $user->id,
            'body' => $data['reply'],
        ]);
    }

    public function destroy(ProductReview $review): void
    {
        $review->loadMissing('product');
        $product = $review->product;
        $mediaPaths = $review->media_paths ?: [];

        DB::transaction(function () use ($review, $product): void {
            $review->delete();

            if ($product instanceof Product) {
                $this->refreshProductRating($product);
            }
        });

        if ($mediaPaths !== []) {
            Storage::disk('public')->delete($mediaPaths);
        }
    }

    private function refreshProductRating(Product $product): void
    {
        $average = ProductReview::query()
            ->whereBelongsTo($product)
            ->avg('rating');

        $product->forceFill([
            'rating' => round((float) $average, 1),
        ])->save();
    }

    private function authorName(?string $displayName, User $user): string
    {
        $name = trim((string) $displayName);

        return $name !== '' ? $name : $user->name;
    }

    /**
     * @param  array<int, UploadedFile>  $media
     * @return array<int, string>
     */
    private function storeMedia(array $media): array
    {
        return collect($media)
            ->take(3)
            ->map(fn (UploadedFile $file): string => $file->store('reviews', 'public'))
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, ProductReview>  $reviews
     * @return array<string, mixed>
     */
    private function summary(Collection $reviews): array
    {
        if ($reviews->isEmpty()) {
            return $this->emptySummary();
        }

        return [
            'average' => round((float) $reviews->avg('rating'), 1),
            'total' => $reviews->count(),
            'with_comments' => $reviews->whereNotNull('body')->count(),
            'with_media' => $reviews->filter(fn (ProductReview $review): bool => filled($review->media_paths))->count(),
            'distribution' => collect([5, 4, 3, 2, 1])
                ->mapWithKeys(fn (int $rating): array => [$rating => $reviews->where('rating', $rating)->count()])
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptySummary(): array
    {
        return [
            'average' => 0.0,
            'total' => 0,
            'with_comments' => 0,
            'with_media' => 0,
            'distribution' => [
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function presentReview(ProductReview $review, Product $product, int $index, ?User $viewer): array
    {
        return [
            'avatar' => $review->user?->profilePhotoUrl(),
            'date' => $review->created_at?->format('Y-m-d g:i A') ?? '',
            'can_delete' => $viewer instanceof User && $review->user_id === $viewer->id,
            'is_anonymous' => $review->is_anonymous,
            'id' => $review->id,
            'is_liked' => (bool) ($review->liked_by_current_user ?? false),
            'likes' => (int) ($review->likes_count ?? 0),
            'media' => collect($review->media_paths ?: [])
                ->map(fn (string $path): string => Storage::disk('public')->url($path))
                ->all(),
            'name' => $review->displayName(),
            'page' => (int) floor($index / 5) + 1,
            'quote' => $review->body,
            'rating' => $review->rating,
            'replies' => $review->replies
                ->map(fn (ProductReviewReply $reply): array => $this->presentReply($reply, $viewer))
                ->all(),
            'variation' => $product->title,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function presentReply(ProductReviewReply $reply, ?User $viewer): array
    {
        return [
            'body' => $reply->body,
            'can_delete' => $viewer instanceof User && $reply->user_id === $viewer->id,
            'date' => $reply->created_at?->format('Y-m-d g:i A') ?? '',
            'id' => $reply->id,
            'name' => $reply->user?->name ?? 'Customer',
        ];
    }
}
