@props([
    'product',
    'canReview' => false,
])

<section class="mx-auto mt-10 max-w-[86rem] px-4 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-white/80 bg-white/94 p-5 shadow-[0_24px_58px_-42px_rgba(15,23,42,0.28)] sm:p-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-lg font-bold text-love-pink-500">Share your experience</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-950">Write a review</h2>
            </div>
        </div>

        @if (session('status'))
            <p class="mt-5 rounded-xl border border-love-blue-100 bg-love-blue-100/70 px-4 py-3 text-sm font-bold text-love-blue-500">{{ session('status') }}</p>
        @endif

        @auth
            @if ($canReview)
                <form class="mt-6 grid gap-5 lg:grid-cols-[0.9fr_1.1fr]" action="{{ route('products.reviews.store', $product['slug']) }}" method="POST" enctype="multipart/form-data" data-review-form>
                    @csrf

                    <div class="grid gap-4">
                        <label class="grid gap-2 text-sm font-semibold text-slate-700">
                            Display name
                            <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="display_name" type="text" value="{{ old('display_name', auth()->user()->name) }}" placeholder="Example: Maria A.">
                            @error('display_name')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-love-pink-100 bg-love-pink-100/60 px-4 py-3 text-sm font-semibold text-[#512438] transition hover:bg-love-pink-100">
                            <span class="min-w-0">
                                <span class="block">Anonymous mode</span>
                                <span class="mt-1 block text-xs font-medium text-[#9a6c7b]">Your name will show partly hidden, like J**o S**r.</span>
                            </span>
                            <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-500 focus:ring-love-pink-200" name="is_anonymous" type="checkbox" value="1" @checked(old('is_anonymous'))>
                        </label>

                        <div class="grid gap-2 text-sm font-semibold text-slate-700">
                            <span>Rating</span>
                            <div class="flex items-center gap-1" data-review-rating>
                                @for ($star = 1; $star <= 5; $star++)
                                    <button class="text-amber-400 transition hover:-translate-y-0.5" type="button" aria-label="{{ $star }} star rating" aria-pressed="true" data-review-star="{{ $star }}">
                                        <svg class="h-7 w-7 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                                        </svg>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" value="{{ old('rating', 5) }}" data-review-rating-input>
                            </div>
                            @error('rating')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <label class="grid gap-2 text-sm font-semibold text-slate-700">
                            Add media
                            <input class="rounded-xl border border-dashed border-love-blue-200 bg-love-blue-100/40 px-4 py-3 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-love-blue-500" name="media[]" type="file" accept="image/*" multiple>
                            @error('media')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                            @error('media.*')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="grid gap-4">
                        <label class="grid gap-2 text-sm font-semibold text-slate-700">
                            Review
                            <textarea class="min-h-44 resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-7 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="review" placeholder="Tell other customers what you liked about this product." required>{{ old('review') }}</textarea>
                            @error('review')
                                <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm font-medium text-love-blue-500 opacity-0" data-review-form-status>Thanks for sharing your review.</p>
                            <button class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_34px_-24px_rgba(15,23,42,0.8)] transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="submit">
                                Submit review
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="mt-6 rounded-xl border border-dashed border-love-pink-200 bg-white px-5 py-6">
                    <p class="text-sm font-semibold text-slate-700">Reviews can be submitted after this product is available in the shop database.</p>
                </div>
            @endif
        @else
            <div class="mt-6 flex flex-col gap-4 rounded-xl border border-love-pink-100 bg-love-cream p-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-semibold text-[#512438]">Sign in to leave a review and choose anonymous mode.</p>
                <a class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" href="{{ route('login') }}">
                    Sign in to review
                </a>
            </div>
        @endauth
    </div>
</section>
