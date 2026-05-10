<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    public function __construct(private ProductCatalog $catalog) {}

    /**
     * @return array{items: Collection<int, array<string, mixed>>, count: int, subtotal: float, formatted_subtotal: string}
     */
    public function summary(Request $request): array
    {
        $items = $this->items($request);
        $subtotal = (float) $items->sum('line_total');

        return [
            'items' => $items,
            'count' => (int) $items->sum('quantity'),
            'subtotal' => $subtotal,
            'formatted_subtotal' => $this->formatPeso($subtotal),
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function items(Request $request): Collection
    {
        return collect($this->quantities($request))
            ->map(function (int $quantity, string $slug): ?array {
                $product = $this->catalog->find($slug);

                if ($product === null) {
                    return null;
                }

                $safeQuantity = min($quantity, (int) $product['stock']);
                $lineTotal = (float) $product['price'] * $safeQuantity;

                return [
                    'slug' => $slug,
                    'title' => $product['title'],
                    'price' => (float) $product['price'],
                    'formatted_price' => $this->formatPeso((float) $product['price']),
                    'image' => $product['image'],
                    'quantity' => $safeQuantity,
                    'stock' => (int) $product['stock'],
                    'note' => $product['category'].' dessert',
                    'line_total' => $lineTotal,
                    'formatted_line_total' => $this->formatPeso($lineTotal),
                ];
            })
            ->filter()
            ->values();
    }

    public function add(Request $request, string $slug, int $quantity = 1): array
    {
        $product = $this->productOrFail($slug);
        $quantities = $this->quantities($request);
        $currentQuantity = $quantities[$slug] ?? 0;

        $quantities[$slug] = min($currentQuantity + $quantity, (int) $product['stock']);
        $this->storeQuantities($request, $quantities);

        return $this->summary($request);
    }

    public function update(Request $request, string $slug, int $quantity): array
    {
        $product = $this->productOrFail($slug);
        $quantities = $this->quantities($request);
        $quantities[$slug] = min($quantity, (int) $product['stock']);

        $this->storeQuantities($request, $quantities);

        return $this->summary($request);
    }

    public function remove(Request $request, string $slug): array
    {
        $quantities = $this->quantities($request);
        unset($quantities[$slug]);

        $this->storeQuantities($request, $quantities);

        return $this->summary($request);
    }

    public function count(Request $request): int
    {
        return (int) array_sum($this->quantities($request));
    }

    public function mergeSessionIntoUser(Store $session, User $user): void
    {
        $sessionQuantities = $this->sessionQuantities($session);

        if ($sessionQuantities === []) {
            return;
        }

        $databaseQuantities = $this->databaseQuantities($user);

        foreach ($sessionQuantities as $slug => $quantity) {
            $product = $this->catalog->find($slug);

            if ($product === null) {
                continue;
            }

            $databaseQuantities[$slug] = min(
                ($databaseQuantities[$slug] ?? 0) + $quantity,
                (int) $product['stock'],
            );
        }

        $this->storeDatabaseQuantities($user, $databaseQuantities);
        $session->forget(self::SESSION_KEY);
    }

    /**
     * @return array<string, int>
     */
    private function quantities(Request $request): array
    {
        $user = $request->user();

        if ($user instanceof User) {
            return $this->databaseQuantities($user);
        }

        return $this->sessionQuantities($request->session());
    }

    /**
     * @return array<string, int>
     */
    private function sessionQuantities(Store $session): array
    {
        $items = $session->get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (mixed $quantity): int => (int) $quantity)
            ->filter(fn (int $quantity): bool => $quantity > 0)
            ->all();
    }

    /**
     * @return array<string, int>
     */
    private function databaseQuantities(User $user): array
    {
        return CartItem::query()
            ->whereBelongsTo($user)
            ->pluck('quantity', 'product_slug')
            ->map(fn (mixed $quantity): int => (int) $quantity)
            ->filter(fn (int $quantity): bool => $quantity > 0)
            ->all();
    }

    /**
     * @param  array<string, int>  $quantities
     */
    private function storeQuantities(Request $request, array $quantities): void
    {
        $user = $request->user();

        if ($user instanceof User) {
            $this->storeDatabaseQuantities($user, $quantities);

            return;
        }

        $request->session()->put(self::SESSION_KEY, $quantities);
    }

    /**
     * @param  array<string, int>  $quantities
     */
    private function storeDatabaseQuantities(User $user, array $quantities): void
    {
        $activeSlugs = array_keys($quantities);

        CartItem::query()
            ->whereBelongsTo($user)
            ->when($activeSlugs !== [], fn ($query) => $query->whereNotIn('product_slug', $activeSlugs))
            ->delete();

        foreach ($quantities as $slug => $quantity) {
            CartItem::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_slug' => $slug,
                ],
                [
                    'quantity' => $quantity,
                ],
            );
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

    private function formatPeso(float $amount): string
    {
        return "\u{20B1}".number_format($amount, 2);
    }
}
