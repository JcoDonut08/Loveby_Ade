<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchAssistant
{
    private const RECENT_SEARCHES_KEY = 'store.recent_searches';

    private const RECENT_SEARCH_LIMIT = 8;

    public function __construct(private ProductCatalog $catalog) {}

    public function remember(Request $request, ?string $term): void
    {
        $term = $this->normalize($term);

        if ($term === null) {
            return;
        }

        $recentSearches = collect($this->recentTerms($request))
            ->reject(fn (string $recentTerm): bool => Str::lower($recentTerm) === Str::lower($term))
            ->prepend($term)
            ->take(self::RECENT_SEARCH_LIMIT)
            ->values()
            ->all();

        $request->session()->put(self::RECENT_SEARCHES_KEY, $recentSearches);
    }

    public function forget(Request $request, ?string $term): void
    {
        $term = $this->normalize($term);

        if ($term === null) {
            return;
        }

        $recentSearches = collect($this->recentTerms($request))
            ->reject(fn (string $recentTerm): bool => Str::lower($recentTerm) === Str::lower($term))
            ->values()
            ->all();

        $request->session()->put(self::RECENT_SEARCHES_KEY, $recentSearches);
    }

    /**
     * @return array<int, array{title: string, url: string}>
     */
    public function recent(Request $request): array
    {
        return collect($this->recentTerms($request))
            ->map(fn (string $term): array => [
                'title' => $term,
                'url' => route('products.index', ['search' => $term]),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{title: string, subtitle: string, url: string}>
     */
    public function suggestions(?string $query): array
    {
        $query = $this->normalize($query);

        if ($query === null) {
            return [];
        }

        return $this->catalog->all()
            ->map(fn (array $product): array => [
                'product' => $product,
                'score' => $this->score($product, $query),
            ])
            ->filter(fn (array $match): bool => $match['score'] > 0)
            ->sortByDesc('score')
            ->take(6)
            ->map(fn (array $match): array => [
                'title' => $match['product']['title'],
                'subtitle' => $match['product']['category'].' - '.$match['product']['formatted_price'],
                'url' => route('products.index', ['search' => $match['product']['title']]),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function recentTerms(Request $request): array
    {
        $recentSearches = $request->session()->get(self::RECENT_SEARCHES_KEY, []);

        if (! is_array($recentSearches)) {
            return [];
        }

        return collect($recentSearches)
            ->filter(fn (mixed $term): bool => is_string($term) && $this->normalize($term) !== null)
            ->map(fn (string $term): string => $this->normalize($term))
            ->filter()
            ->values()
            ->all();
    }

    private function score(array $product, string $query): int
    {
        $title = Str::lower((string) $product['title']);
        $category = Str::lower((string) $product['category']);
        $query = Str::lower($query);

        if ($title === $query) {
            return 100;
        }

        if (Str::startsWith($title, $query)) {
            return 90;
        }

        if (str_contains($title, $query)) {
            return 80;
        }

        if (Str::startsWith($category, $query)) {
            return 60;
        }

        if (str_contains($category, $query)) {
            return 50;
        }

        return $this->tokenScore($product, $query);
    }

    private function tokenScore(array $product, string $query): int
    {
        $tokens = Str::of($query)
            ->explode(' ')
            ->filter(fn (string $token): bool => mb_strlen($token) >= 2);

        if ($tokens->isEmpty()) {
            return 0;
        }

        $haystack = Str::lower(implode(' ', [
            $product['title'],
            $product['category'],
        ]));

        return $tokens->every(fn (string $token): bool => str_contains($haystack, $token))
            ? 15
            : 0;
    }

    private function normalize(?string $term): ?string
    {
        if ($term === null) {
            return null;
        }

        $term = Str::squish($term);

        return $term === '' ? null : $term;
    }
}
