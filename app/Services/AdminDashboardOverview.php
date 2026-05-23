<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminDashboardOverview
{
    public function __construct(private OrderAccountingService $accounting) {}

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $orders = Order::query()
            ->with(['items', 'user'])
            ->latest()
            ->get();
        $salesOrders = $orders->filter(fn (Order $order): bool => $this->accounting->countsAsPaid($order));
        $customers = User::query()
            ->where(fn ($query) => $query
                ->where('role', '!=', 'admin')
                ->orWhereNull('role'))
            ->get();

        $lowStockProducts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->orderBy('title')
            ->get();

        return [
            'metrics' => $this->metrics($orders, $salesOrders, $customers),
            'notificationsCount' => $orders->where('status', Order::STATUS_PENDING)->count(),
            'lowStock' => $this->lowStock($lowStockProducts),
            'salesPerformance' => $this->salesPerformance($salesOrders),
            'topDesserts' => $this->topDesserts($orders),
            'userActivity' => $this->userActivity($customers),
            'recentOrders' => $this->recentOrders($orders),
            'customerActivity' => $this->customerActivity($orders, $customers),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  Collection<int, Order>  $salesOrders
     * @param  Collection<int, User>  $customers
     * @return array<int, array<string, string>>
     */
    private function metrics(Collection $orders, Collection $salesOrders, Collection $customers): array
    {
        $currentStart = now()->subDays(30);
        $previousStart = now()->subDays(60);
        $currentSalesOrders = $this->between($salesOrders, $currentStart, now());
        $previousSalesOrders = $this->between($salesOrders, $previousStart, $currentStart);
        $currentOrders = $this->between($orders, $currentStart, now());
        $previousOrders = $this->between($orders, $previousStart, $currentStart);
        $currentCustomers = $customers->filter(fn (User $user): bool => $user->created_at?->greaterThanOrEqualTo($currentStart) === true)->count();
        $previousCustomers = $customers->filter(fn (User $user): bool => $user->created_at?->betweenIncluded($previousStart, $currentStart) === true)->count();

        $currentRevenue = $currentSalesOrders->sum(fn (Order $order): float => (float) $order->total);
        $previousRevenue = $previousSalesOrders->sum(fn (Order $order): float => (float) $order->total);
        $currentAverage = $currentSalesOrders->avg(fn (Order $order): float => (float) $order->total) ?: 0.0;
        $previousAverage = $previousSalesOrders->avg(fn (Order $order): float => (float) $order->total) ?: 0.0;
        $pendingOrders = $orders->where('status', Order::STATUS_PENDING);
        $previousPending = $orders
            ->filter(fn (Order $order): bool => $order->status === Order::STATUS_PENDING
                && $order->created_at?->betweenIncluded($previousStart, $currentStart) === true)
            ->count();

        return [
            [
                'title' => 'Revenue',
                'value' => $this->money($currentRevenue),
                'trend' => $this->trend($currentRevenue, $previousRevenue),
                'direction' => $this->direction($currentRevenue, $previousRevenue),
                'icon' => 'revenue',
                'tone' => 'pink',
            ],
            [
                'title' => 'Orders',
                'value' => number_format($currentOrders->count()),
                'trend' => $this->trend($currentOrders->count(), $previousOrders->count()),
                'direction' => $this->direction($currentOrders->count(), $previousOrders->count()),
                'icon' => 'orders',
                'tone' => 'purple',
            ],
            [
                'title' => 'Pending',
                'value' => number_format($pendingOrders->count()),
                'trend' => $this->trend($pendingOrders->count(), $previousPending),
                'direction' => $this->direction($pendingOrders->count(), $previousPending),
                'icon' => 'pending',
                'tone' => 'amber',
            ],
            [
                'title' => 'Customers',
                'value' => number_format($customers->count()),
                'trend' => $this->trend($currentCustomers, $previousCustomers),
                'direction' => $this->direction($currentCustomers, $previousCustomers),
                'icon' => 'customers',
                'tone' => 'blue',
            ],
            [
                'title' => 'Avg. order',
                'value' => $this->money($currentAverage, 1),
                'trend' => $this->trend($currentAverage, $previousAverage),
                'direction' => $this->direction($currentAverage, $previousAverage),
                'icon' => 'average',
                'tone' => 'green',
            ],
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return Collection<int, Order>
     */
    private function between(Collection $orders, CarbonInterface $start, CarbonInterface $end): Collection
    {
        return $orders->filter(fn (Order $order): bool => $order->created_at?->betweenIncluded($start, $end) === true);
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array<string, mixed>
     */
    private function lowStock(Collection $products): array
    {
        return [
            'count' => $products->count(),
            'products' => $products
                ->take(3)
                ->map(fn (Product $product): string => $product->title)
                ->values()
                ->all(),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<string, array<string, mixed>>
     */
    private function salesPerformance(Collection $orders): array
    {
        $now = $this->dashboardNow();

        return [
            'daily' => $this->salesPeriod(
                $this->salesBuckets($orders, collect(range(0, 22, 2)), function (Order $order) use ($now): ?int {
                    $createdAt = $this->dashboardTime($order->created_at);

                    return $createdAt?->isSameDay($now) === true ? (int) floor((int) $createdAt->format('G') / 2) * 2 : null;
                }),
                'Today from '.$orders->filter(fn (Order $order): bool => $this->dashboardTime($order->created_at)?->isSameDay($now) === true)->count().' dessert orders',
                fn (int $hour): string => CarbonImmutable::createFromTime($hour)->format('g A')
            ),
            'weekly' => $this->salesPeriod(
                $this->salesBuckets($orders, collect(range(0, 6)), function (Order $order) use ($now): ?int {
                    $createdAt = $this->dashboardTime($order->created_at);

                    if ($createdAt?->betweenIncluded($now->startOfWeek(), $now->endOfWeek()) !== true) {
                        return null;
                    }

                    return (int) $createdAt->isoWeekday() - 1;
                }),
                'Track your bakery\'s sweet revenue',
                fn (int $day): string => $now->startOfWeek()->addDays($day)->format('D')
            ),
            'monthly' => $this->salesPeriod(
                $this->salesBuckets($orders, collect(range(1, 5)), function (Order $order) use ($now): ?int {
                    $createdAt = $this->dashboardTime($order->created_at);

                    return $createdAt?->isSameMonth($now) === true ? (int) ceil((int) $createdAt->format('j') / 7) : null;
                }),
                'This month across all sweet categories',
                fn (int $week): string => 'Week '.$week
            ),
            'yearly' => $this->salesPeriod(
                $this->salesBuckets($orders, collect(range(1, 4)), function (Order $order) use ($now): ?int {
                    $createdAt = $this->dashboardTime($order->created_at);

                    return $createdAt?->isSameYear($now) === true ? (int) ceil((int) $createdAt->format('n') / 3) : null;
                }),
                'Year-to-date revenue from repeat buyers',
                fn (int $quarter): string => 'Q'.$quarter
            ),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  Collection<int, int>  $keys
     * @return array<int, float>
     */
    private function salesBuckets(Collection $orders, Collection $keys, callable $bucketFor): array
    {
        $buckets = $keys->mapWithKeys(fn (int $key): array => [$key => 0.0])->all();

        $orders->each(function (Order $order) use (&$buckets, $bucketFor): void {
            $bucket = $bucketFor($order);

            if ($bucket !== null && array_key_exists($bucket, $buckets)) {
                $buckets[$bucket] += (float) $order->total;
            }
        });

        return $buckets;
    }

    /**
     * @param  array<int, float>  $buckets
     * @return array<string, mixed>
     */
    private function salesPeriod(array $buckets, string $caption, callable $labelFor): array
    {
        $max = max(max($buckets), 1);

        return [
            'total' => $this->money(array_sum($buckets)),
            'caption' => $caption,
            'axis' => [
                number_format($max),
                number_format($max * 0.75),
                number_format($max * 0.5),
                number_format($max * 0.25),
                '0',
            ],
            'bars' => collect($buckets)
                ->map(fn (float $total, int $key): array => [
                    'label' => $labelFor($key),
                    'amount' => $this->money($total),
                    'height' => $total > 0 ? max(16, (int) round(($total / $max) * 294)) : 0,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<int, array<string, mixed>>
     */
    private function topDesserts(Collection $orders): array
    {
        $now = $this->dashboardNow();

        $items = $orders
            ->filter(fn (Order $order): bool => $order->status !== Order::STATUS_CANCELLED
                && $this->dashboardTime($order->created_at)?->greaterThanOrEqualTo($now->subWeek()) === true)
            ->flatMap->items
            ->filter(fn (OrderItem $item): bool => $item instanceof OrderItem);

        $totalQuantity = max(1, $items->sum('quantity'));
        $colors = [
            ['stroke' => '#f472a8', 'class' => 'text-love-pink-400', 'dot' => 'bg-love-pink-400'],
            ['stroke' => '#fbbf24', 'class' => 'text-amber-400', 'dot' => 'bg-amber-400'],
            ['stroke' => '#7dd3fc', 'class' => 'text-love-blue-300', 'dot' => 'bg-love-blue-300'],
            ['stroke' => '#c084fc', 'class' => 'text-purple-400', 'dot' => 'bg-purple-400'],
        ];
        $offset = 0.0;

        return $items
            ->groupBy('category')
            ->map(fn (Collection $categoryItems, string $category): array => [
                'label' => $category,
                'quantity' => $categoryItems->sum('quantity'),
            ])
            ->sortByDesc('quantity')
            ->take(4)
            ->values()
            ->map(function (array $category, int $index) use (&$offset, $totalQuantity, $colors): array {
                $percent = round(((int) $category['quantity'] / $totalQuantity) * 100, 1);
                $segment = [
                    ...$category,
                    ...($colors[$index] ?? $colors[0]),
                    'percent' => $percent,
                    'offset' => -$offset,
                ];
                $offset += $percent;

                return $segment;
            })
            ->all();
    }

    /**
     * @param  Collection<int, User>  $customers
     * @return array<string, mixed>
     */
    private function userActivity(Collection $customers): array
    {
        $now = $this->dashboardNow();
        $days = collect(range(0, 6))
            ->map(fn (int $index): CarbonInterface => $now->startOfWeek()->addDays($index));
        $counts = $days->map(fn (CarbonInterface $day): int => $customers
            ->filter(fn (User $user): bool => $this->dashboardTime($user->last_active_at)?->isSameDay($day) === true || $this->dashboardTime($user->created_at)?->isSameDay($day) === true)
            ->count());
        $max = max($counts->max() ?? 0, 1);
        $points = $counts
            ->values()
            ->map(fn (int $count, int $index): array => [
                'x' => [72, 192, 322, 462, 552, 612, 724][$index],
                'y' => 284 - (int) round(($count / $max) * 240),
                'label' => $days[$index]->format('D'),
            ])
            ->all();

        return [
            'axis' => [
                $max,
                (int) round($max * 0.75),
                (int) round($max * 0.5),
                (int) round($max * 0.25),
                0,
            ],
            'points' => $points,
            'path' => collect($points)
                ->map(fn (array $point, int $index): string => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['y'])
                ->join(' '),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<int, array<string, string>>
     */
    private function recentOrders(Collection $orders): array
    {
        return $orders
            ->take(4)
            ->map(fn (Order $order): array => [
                'number' => $order->order_number,
                'customer' => $order->full_name,
                'item' => $this->orderItemLabel($order),
                'total' => $this->money((float) $order->total, 2),
                'status' => Str::headline($order->status),
                'statusTone' => $this->statusTone($order->status),
            ])
            ->values()
            ->all();
    }

    private function orderItemLabel(Order $order): string
    {
        $firstItem = $order->items->first();

        if (! $firstItem instanceof OrderItem) {
            return 'No products';
        }

        $remaining = $order->items->count() - 1;

        return $remaining > 0 ? $firstItem->product_title.' +'.$remaining.' more' : $firstItem->product_title;
    }

    /**
     * @return array<string, string>
     */
    private function statusTone(string $status): array
    {
        return match ($status) {
            Order::STATUS_DELIVERED => [
                'badge' => 'bg-emerald-100 text-emerald-600',
                'dot' => 'bg-emerald-400',
            ],
            Order::STATUS_OUT_FOR_DELIVERY => [
                'badge' => 'bg-love-blue-200 text-[#23445c]',
                'dot' => 'bg-love-blue-400',
            ],
            Order::STATUS_PREPARING => [
                'badge' => 'bg-amber-100 text-amber-700',
                'dot' => 'bg-amber-400',
            ],
            Order::STATUS_CANCELLED => [
                'badge' => 'bg-rose-100 text-rose-600',
                'dot' => 'bg-rose-400',
            ],
            default => [
                'badge' => 'bg-love-pink-100 text-love-pink-500',
                'dot' => 'bg-love-pink-400',
            ],
        };
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  Collection<int, User>  $customers
     * @return array<int, array<string, string>>
     */
    private function customerActivity(Collection $orders, Collection $customers): array
    {
        $orderEvents = collect($orders->take(3)->map(fn (Order $order): array => [
            'type' => 'order',
            'name' => $order->full_name,
            'message' => 'placed an order',
            'detail' => $order->order_number.' - '.$this->money((float) $order->total, 2).' - '.$this->relativeTime($order->created_at),
            'time' => $order->created_at,
        ]));
        $customerEvents = collect($customers
            ->sortByDesc('created_at')
            ->take(2)
            ->map(fn (User $user): array => [
                'type' => 'register',
                'name' => $user->name,
                'message' => 'registered an account',
                'detail' => 'New customer - '.$this->relativeTime($user->created_at),
                'time' => $user->created_at,
            ]));

        return $orderEvents
            ->merge($customerEvents)
            ->sortByDesc('time')
            ->take(4)
            ->values()
            ->map(fn (array $event): array => [
                'type' => $event['type'],
                'name' => $event['name'],
                'message' => $event['message'],
                'detail' => $event['detail'],
                'tone' => $event['type'] === 'order'
                    ? 'bg-love-cream text-[#512438]'
                    : 'bg-love-pink-100 text-love-pink-500',
            ])
            ->all();
    }

    private function relativeTime(?CarbonInterface $time): string
    {
        return $time instanceof CarbonInterface ? $time->diffForHumans() : 'Unknown time';
    }

    private function trend(float|int $current, float|int $previous): string
    {
        if ((float) $previous <= 0.0) {
            return (float) $current > 0.0 ? '100.0%' : '0.0%';
        }

        return number_format(abs(((float) $current - (float) $previous) / (float) $previous) * 100, 1).'%';
    }

    private function direction(float|int $current, float|int $previous): string
    {
        return (float) $current < (float) $previous ? 'down' : 'up';
    }

    private function money(float $amount, int $decimals = 0): string
    {
        return '₱'.number_format($amount, $decimals);
    }

    private function dashboardNow(): CarbonImmutable
    {
        return CarbonImmutable::instance(now($this->dashboardTimezone()));
    }

    private function dashboardTime(?CarbonInterface $time): ?CarbonImmutable
    {
        return $time instanceof CarbonInterface
            ? CarbonImmutable::instance($time)->timezone($this->dashboardTimezone())
            : null;
    }

    private function dashboardTimezone(): string
    {
        return (string) config('app.business_timezone', 'Asia/Manila');
    }
}
