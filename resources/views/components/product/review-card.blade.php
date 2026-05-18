@props([
    'name',
    'date',
    'variation',
    'rating' => 5,
    'quote',
    'reviewId',
    'likes' => '0',
    'isLiked' => false,
    'canDelete' => false,
    'avatar' => null,
    'media' => [],
    'replies' => [],
])

@php
    $initial = Str::of($name)->trim()->substr(0, 1)->upper()->toString() ?: '?';
@endphp

<article {{ $attributes->merge(['class' => 'border-b border-slate-100 py-6 last:border-b-0']) }}>
    <div class="grid gap-4 sm:grid-cols-[3rem_1fr]">
        @if ($avatar)
            <img class="h-12 w-12 rounded-full object-cover ring-2 ring-love-pink-100" src="{{ $avatar }}" alt="{{ $name }} profile photo" loading="lazy">
        @else
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-love-pink-100 text-sm font-extrabold text-love-pink-500 ring-2 ring-love-pink-100">{{ $initial }}</span>
        @endif

        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-950">{{ $name }}</p>

            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <x-product.rating-stars :rating="$rating" size="h-4 w-4" />
                <span>{{ $date }}</span>
                <span>|</span>
                <span>{{ $variation }}</span>
            </div>

            <p class="mt-4 max-w-5xl text-sm leading-7 text-slate-700 sm:text-base">{{ $quote }}</p>

            @if (filled($media))
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($media as $reviewMedia)
                        <img class="h-20 w-20 rounded-lg object-cover" src="{{ $reviewMedia }}" alt="Review media" loading="lazy">
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500">
                @auth
                    <form action="{{ route('products.reviews.likes.toggle', $reviewId) }}" method="POST">
                        @csrf
                        <button class="{{ $isLiked ? 'border-love-pink-200 bg-love-pink-100 text-love-pink-500' : 'border-slate-200 bg-white text-slate-500 hover:border-love-pink-200 hover:text-love-pink-500' }} inline-flex items-center gap-2 rounded-full border px-3 py-2 transition" type="submit" aria-pressed="{{ $isLiked ? 'true' : 'false' }}">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21H4.75A1.75 1.75 0 0 1 3 19.25v-7.5A1.75 1.75 0 0 1 4.75 10H7.5v11Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10l3.25-6.25A2 2 0 0 1 14.5 4.7V9h4.25a2 2 0 0 1 1.96 2.4l-1.2 6A4.5 4.5 0 0 1 15.1 21H7.5" />
                            </svg>
                            <span>{{ $isLiked ? 'Liked' : 'Like' }}</span>
                            <span>{{ $likes }}</span>
                        </button>
                    </form>

                    @if ($canDelete)
                        <form action="{{ route('products.reviews.destroy', $reviewId) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex items-center gap-2 rounded-full border border-red-100 bg-white px-3 py-2 text-red-500 transition hover:border-red-200 hover:text-red-600" type="submit">
                                Delete review
                            </button>
                        </form>
                    @endif
                @else
                    <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 transition hover:border-love-pink-200 hover:text-love-pink-500" href="{{ route('login') }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21H4.75A1.75 1.75 0 0 1 3 19.25v-7.5A1.75 1.75 0 0 1 4.75 10H7.5v11Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 10l3.25-6.25A2 2 0 0 1 14.5 4.7V9h4.25a2 2 0 0 1 1.96 2.4l-1.2 6A4.5 4.5 0 0 1 15.1 21H7.5" />
                        </svg>
                        <span>Like</span>
                        <span>{{ $likes }}</span>
                    </a>
                @endauth
            </div>

            <div class="mt-5 space-y-3">
                @foreach ($replies as $reply)
                    <div class="rounded-xl border border-love-blue-100 bg-love-blue-100/40 px-4 py-3">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                                <span class="text-slate-800">{{ $reply['name'] }}</span>
                                <span>{{ $reply['date'] }}</span>
                            </div>

                            @if ($reply['can_delete'])
                                <form action="{{ route('products.reviews.replies.destroy', $reply['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 transition hover:text-red-600" type="submit">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $reply['body'] }}</p>
                    </div>
                @endforeach
            </div>

            @auth
                <form class="mt-4 flex flex-col gap-3 rounded-xl border border-slate-100 bg-slate-50/80 p-3 sm:flex-row sm:items-start" action="{{ route('products.reviews.replies.store', $reviewId) }}" method="POST">
                    @csrf
                    <input type="hidden" name="review_id" value="{{ $reviewId }}">
                    <label class="min-w-0 flex-1">
                        <span class="sr-only">Reply to {{ $name }}</span>
                        <textarea class="min-h-20 w-full resize-y rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100" name="reply" placeholder="Write a reply">{{ old('review_id') == $reviewId ? old('reply') : '' }}</textarea>
                        @if (old('review_id') == $reviewId)
                            @error('reply')
                                <span class="mt-2 block text-xs font-medium text-red-500">{{ $message }}</span>
                            @enderror
                        @endif
                    </label>
                    <button class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-love-pink-500" type="submit">
                        Reply
                    </button>
                </form>
            @else
                <a class="mt-4 inline-flex text-sm font-semibold text-love-pink-500 hover:text-love-pink-600" href="{{ route('login') }}">
                    Sign in to reply
                </a>
            @endauth
        </div>
    </div>
</article>
