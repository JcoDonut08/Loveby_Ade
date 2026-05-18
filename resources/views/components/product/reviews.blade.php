@props([
    'reviews',
    'summary',
])

@php
    $reviews = collect($reviews);
    $totalReviews = (int) ($summary['total'] ?? 0);
    $pageCount = max(1, (int) ceil($totalReviews / 5));
@endphp

<section id="reviews" class="mx-auto mt-12 max-w-[86rem] px-4 sm:px-6 lg:px-8" data-review-section>
    <div class="rounded-2xl border border-white/80 bg-white/94 p-5 shadow-[0_24px_58px_-42px_rgba(15,23,42,0.28)] sm:p-8">
        <h2 class="text-2xl font-bold text-slate-950">Product Ratings</h2>

        <div class="mt-5 grid gap-5 rounded-xl border border-love-pink-100 bg-love-cream p-5 lg:grid-cols-[11rem_1fr] lg:items-center">
            <div>
                <p class="text-4xl font-semibold text-love-orange-500">{{ number_format((float) ($summary['average'] ?? 0), 1) }} <span class="text-base font-medium text-love-orange-500">out of 5</span></p>
                <x-product.rating-stars :rating="$summary['average'] ?? 0" size="h-6 w-6" class="mt-3 text-love-orange-400" />
                <p class="mt-2 text-sm font-semibold text-slate-500">{{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
            </div>

            <div class="flex flex-wrap gap-2" data-review-filters>
                <button class="rounded border border-love-orange-400 bg-white px-5 py-2 text-sm font-medium text-love-orange-500 transition hover:border-love-orange-400 hover:text-love-orange-500" type="button" data-review-filter="all" aria-pressed="true">All ({{ $totalReviews }})</button>
                @foreach ([5, 4, 3, 2, 1] as $rating)
                    <button class="rounded border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 transition hover:border-love-orange-300 hover:text-love-orange-500" type="button" data-review-filter="rating:{{ $rating }}" aria-pressed="false">{{ $rating }} Star ({{ $summary['distribution'][$rating] ?? 0 }})</button>
                @endforeach
                <button class="rounded border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 transition hover:border-love-orange-300 hover:text-love-orange-500" type="button" data-review-filter="comments" aria-pressed="false">With Comments ({{ $summary['with_comments'] ?? 0 }})</button>
                <button class="rounded border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 transition hover:border-love-orange-300 hover:text-love-orange-500" type="button" data-review-filter="media" aria-pressed="false">With Media ({{ $summary['with_media'] ?? 0 }})</button>
            </div>
        </div>

        @if ($reviews->isEmpty())
            <div class="mt-6 rounded-xl border border-dashed border-love-pink-200 bg-white px-5 py-8 text-center">
                <p class="text-base font-extrabold text-slate-950">No reviews yet</p>
                <p class="mt-2 text-sm font-medium text-slate-500">Be the first customer to share what you think about this product.</p>
            </div>
        @else
            <div class="mt-6">
                @foreach ($reviews as $review)
                    <x-product.review-card
                        :class="$review['page'] === 1 ? '' : 'hidden'"
                        :data-review-page="$review['page']"
                        :data-review-rating="$review['rating']"
                        :data-review-has-comment="filled($review['quote']) ? 'true' : 'false'"
                        :data-review-has-media="filled($review['media']) ? 'true' : 'false'"
                        :id="'review-'.$review['id']"
                        :review-id="$review['id']"
                        :name="$review['name']"
                        :avatar="$review['avatar']"
                        :can-delete="$review['can_delete']"
                        :date="$review['date']"
                        :variation="$review['variation']"
                        :rating="$review['rating']"
                        :likes="$review['likes']"
                        :is-liked="$review['is_liked']"
                        :media="$review['media']"
                        :quote="$review['quote']"
                        :replies="$review['replies']"
                    />
                @endforeach
            </div>

            <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500" data-review-pagination-status>Showing 1-{{ min(5, $totalReviews) }} of {{ $totalReviews }} reviews</p>
                <div class="flex items-center gap-2">
                    @for ($page = 1; $page <= $pageCount; $page++)
                        <button class="{{ $page === 1 ? 'border-love-pink-300 bg-love-pink-100 text-love-pink-500' : 'border-slate-200 bg-white text-slate-600 hover:border-love-pink-200 hover:text-love-pink-500' }} inline-flex h-10 min-w-10 items-center justify-center rounded-lg border px-4 text-sm font-semibold transition" type="button" @if ($page === 1) aria-current="page" @endif data-review-page-button="{{ $page }}">{{ $page }}</button>
                    @endfor
                </div>
            </div>
        @endif
    </div>
</section>
