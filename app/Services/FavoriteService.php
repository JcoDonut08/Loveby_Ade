<?php

namespace App\Services;

use App\Models\FavoriteItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FavoriteService
{
    private const SESSION_KEY = 'favorites.items';

    public function __construct(private ProductCatalog $catalog) {}

    /**
     * @return array{items: Collection<int, array<string, mixed>>, count: int, slugs: array<int, string>}
     */
    public function summary(Request $request): array
    {
        $slugs = $this->slugs($request);

        return [
            'items' => $this->itemsFromSlugs($slugs),
            'count' => count($slugs),
            'slugs' => array_values($slugs),
        ];
    }

    /**
     * @return array{summary: array{items: Collection<int, array<string, mixed>>, count: int, slugs: array<int, string>}, favorited: bool}
     */
    public function toggle(Request $request, string $slug): array
    {
        $this->productOrFail($slug);

        $slugs = $this->slugs($request);
        $favorited = ! in_array($slug, $slugs, true);

        if ($favorited) {
            $slugs[] = $slug;
        } else {
            $slugs = array_values(array_filter(
                $slugs,
                fn (string $favoriteSlug): bool => $favoriteSlug !== $slug,
            ));
        }

        $this->storeSlugs($request, $slugs);

        return [
            'summary' => $this->summary($request),
            'favorited' => $favorited,
        ];
    }

    /**
     * @return array{items: Collection<int, array<string, mixed>>, count: int, slugs: array<int, string>}
     */
    public function remove(Request $request, string $slug): array
    {
        $slugs = array_values(array_filter(
            $this->slugs($request),
            fn (string $favoriteSlug): bool => $favoriteSlug !== $slug,
        ));

        $this->storeSlugs($request, $slugs);

        return $this->summary($request);
    }

    public function count(Request $request): int
    {
        return count($this->slugs($request));
    }

    public function mergeSessionIntoUser(Store $session, User $user): void
    {
        $sessionSlugs = $this->sessionSlugs($session);

        if ($sessionSlugs === []) {
            return;
        }

        $databaseSlugs = $this->databaseSlugs($user);

        $this->storeDatabaseSlugs($user, array_values(array_unique([
            ...$databaseSlugs,
            ...$sessionSlugs,
        ])));

        $session->forget(self::SESSION_KEY);
    }

    /**
     * @param  array<int, string>  $slugs
     * @return Collection<int, array<string, mixed>>
     */
    private function itemsFromSlugs(array $slugs): Collection
    {
        return collect($slugs)
            ->map(function (string $slug): ?array {
                $product = $this->catalog->find($slug);

                if ($product === null) {
                    return null;
                }

                return [
                    'slug' => $slug,
                    'title' => $product['title'],
                    'price' => (float) $product['price'],
                    'formatted_price' => "\u{20B1}".number_format((float) $product['price'], 2),
                    'image' => $product['image'],
                    'rating' => (float) $product['rating'],
                    'show_url' => $product['show_url'],
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * @return array<int, string>
     */
    private function slugs(Request $request): array
    {
        $user = $request->user();

        if ($user instanceof User) {
            return $this->databaseSlugs($user);
        }

        return $this->sessionSlugs($request->session());
    }

    /**
     * @return array<int, string>
     */
    private function sessionSlugs(Store $session): array
    {
        $items = $session->get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn (mixed $slug): bool => is_string($slug) && $this->catalog->find($slug) !== null)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function databaseSlugs(User $user): array
    {
        return FavoriteItem::query()
            ->whereBelongsTo($user)
            ->latest('id')
            ->pluck('product_slug')
            ->filter(fn (string $slug): bool => $this->catalog->find($slug) !== null)
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $slugs
     */
    private function storeSlugs(Request $request, array $slugs): void
    {
        $user = $request->user();

        if ($user instanceof User) {
            $this->storeDatabaseSlugs($user, $slugs);

            return;
        }

        $request->session()->put(self::SESSION_KEY, array_values(array_unique($slugs)));
    }

    /**
     * @param  array<int, string>  $slugs
     */
    private function storeDatabaseSlugs(User $user, array $slugs): void
    {
        FavoriteItem::query()
            ->whereBelongsTo($user)
            ->when($slugs !== [], fn ($query) => $query->whereNotIn('product_slug', $slugs))
            ->delete();

        foreach (array_unique($slugs) as $slug) {
            FavoriteItem::query()->firstOrCreate([
                'user_id' => $user->id,
                'product_slug' => $slug,
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function productOrFail(string $slug): array
    {
        $product = $this->catalog->find($slug);

        if ($product === null) {
            throw new InvalidArgumentException('Product does not exist.');
        }

        return $product;
    }
}
