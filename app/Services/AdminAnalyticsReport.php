<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminAnalyticsReport
{
    public const PERIOD_TODAY = AdminAnalyticsPeriod::TODAY;

    public const PERIOD_WEEK = AdminAnalyticsPeriod::WEEK;

    public const PERIOD_MONTH = AdminAnalyticsPeriod::MONTH;

    public const PERIOD_YEAR = AdminAnalyticsPeriod::YEAR;

    public function __construct(
        private OrderAccountingService $accounting,
        private AdminAnalyticsPeriod $periods,
    ) {}

    /**
     * @return array<int, string>
     */
    public static function periods(): array
    {
        return AdminAnalyticsPeriod::keys();
    }

    /**
     * @param  array{period?: string|null, search?: string|null}  $filters
     * @return array<string, mixed>
     */
    public function data(array $filters): array
    {
        $periodKey = in_array($filters['period'] ?? null, self::periods(), true)
            ? (string) $filters['period']
            : self::PERIOD_WEEK;
        $search = trim((string) ($filters['search'] ?? ''));
        $period = $this->periods->rangeFor($periodKey);
        $orders = Order::query()
            ->with(['items.product', 'user'])
            ->latest()
            ->get();
        $products = Product::query()
            ->where('is_active', true)
            ->latest()
            ->get();
        $customers = User::query()
            ->where(fn ($query) => $query
                ->where('role', '!=', 'admin')
                ->orWhereNull('role'))
            ->get();
        $periodOrders = $this->ordersBetween($orders, $period['start'], $period['end']);
        $previousOrders = $this->ordersBetween($orders, $period['previous_start'], $period['previous_end']);
        $periodCustomers = $this->customersBetween($customers, $period['start'], $period['end']);
        $previousCustomers = $this->customersBetween($customers, $period['previous_start'], $period['previous_end']);
        $allProductRows = $this->productRows($periodOrders, $products, '');
        $previousProductRows = collect($this->productRows($previousOrders, $products, ''))->keyBy('slug');

        return [
            'filters' => [
                'period' => $periodKey,
                'search' => $search,
            ],
            'periods' => $this->periods->options($periodKey, $search),
            'summary' => $this->summary($periodOrders, $previousOrders, $periodCustomers, $previousCustomers, $customers, $allProductRows, $previousProductRows, $periodKey),
            'salesRows' => $this->salesRows($periodOrders, $search),
            'productRows' => $this->filterRows($allProductRows, $search),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  Collection<int, User>  $periodCustomers
     * @param  Collection<int, User>  $previousCustomers
     * @param  Collection<int, User>  $customers
     * @param  array<int, array<string, mixed>>  $productRows
     * @param  Collection<string, array<string, mixed>>  $previousProductRows
     * @return array<string, array<string, mixed>>
     */
    private function summary(Collection $orders, Collection $previousOrders, Collection $periodCustomers, Collection $previousCustomers, Collection $customers, array $productRows, Collection $previousProductRows, string $period): array
    {
        $reportableOrders = $orders->reject(fn (Order $order): bool => $order->status === Order::STATUS_CANCELLED);
        $previousReportableOrders = $previousOrders->reject(fn (Order $order): bool => $order->status === Order::STATUS_CANCELLED);
        $salesOrders = $orders->filter(fn (Order $order): bool => $this->accounting->countsAsPaid($order));
        $previousSalesOrders = $previousOrders->filter(fn (Order $order): bool => $this->accounting->countsAsPaid($order));
        $currentRevenue = $salesOrders->sum(fn (Order $order): float => (float) $order->total);
        $previousRevenue = $previousSalesOrders->sum(fn (Order $order): float => (float) $order->total);
        $completedOrders = $reportableOrders->where('status', Order::STATUS_DELIVERED)->count();
        $activeOrders = $reportableOrders
            ->reject(fn (Order $order): bool => $order->status === Order::STATUS_DELIVERED)
            ->count();
        $bestProduct = collect($productRows)->sortByDesc('sold')->first();
        $previousBestSold = is_array($bestProduct)
            ? (int) ($previousProductRows[$bestProduct['slug']]['sold'] ?? 0)
            : 0;

        return [
            'revenue' => [
                'amount' => $this->money($currentRevenue, 0),
                'trend' => $this->trend($currentRevenue, $previousRevenue).' vs previous period',
            ],
            'orders' => [
                'count' => number_format($reportableOrders->count()),
                'detail' => number_format($completedOrders).' completed + '.number_format($activeOrders).' active',
                'trend' => $this->trend($reportableOrders->count(), $previousReportableOrders->count()).' order change',
            ],
            'customers' => [
                'count' => number_format($customers->count()),
                'detail' => number_format($periodCustomers->count()).' new customers this period',
                'trend' => $this->trend($periodCustomers->count(), $previousCustomers->count()).' customer growth',
            ],
            'bestProduct' => $this->bestProductSummary($bestProduct, $previousBestSold, $period),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<int, array<string, string>>
     */
    private function salesRows(Collection $orders, string $search): array
    {
        $rows = $orders
            ->reject(fn (Order $order): bool => $order->status === Order::STATUS_CANCELLED)
            ->flatMap(fn (Order $order): Collection => $order->items->map(function (OrderItem $item) use ($order): array {
                $customer = $order->full_name ?: ($order->user?->name ?? 'Customer');

                return [
                    'customer' => $customer,
                    'product' => $item->product_title,
                    'quantity' => number_format($item->quantity),
                    'total' => $this->money((float) $item->line_total),
                    'search' => Str::lower(implode(' ', [
                        $order->order_number,
                        $customer,
                        $order->email_address,
                        $item->product_title,
                        $item->category,
                    ])),
                ];
            }))
            ->values();

        return $this->filterRows($rows->all(), $search);
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  Collection<int, Product>  $products
     * @return array<int, array<string, mixed>>
     */
    private function productRows(Collection $orders, Collection $products, string $search): array
    {
        $productsBySlug = $products->keyBy('slug');
        $items = $orders
            ->reject(fn (Order $order): bool => $order->status === Order::STATUS_CANCELLED)
            ->flatMap->items
            ->filter(fn (OrderItem $item): bool => $item instanceof OrderItem);

        $rows = $items
            ->groupBy('product_slug')
            ->map(function (Collection $items, string $slug) use ($productsBySlug): array {
                /** @var OrderItem $firstItem */
                $firstItem = $items->first();
                $product = $productsBySlug->get($slug) ?? $firstItem->product;
                $sold = (int) $items->sum('quantity');
                $stock = $product instanceof Product ? $product->stock : 0;
                $turnover = $this->turnover($sold, $stock);
                $title = $product instanceof Product ? $product->title : $firstItem->product_title;
                $category = $product instanceof Product ? $product->category : $firstItem->category;

                return [
                    'slug' => $slug,
                    'title' => $title,
                    'category' => $category,
                    'sold' => $sold,
                    'sold_label' => number_format($sold),
                    'stock_label' => number_format($stock).' left',
                    'turnover' => number_format($turnover).'%',
                    'turnover_tone' => $turnover >= 75 ? 'high' : ($turnover >= 50 ? 'medium' : 'low'),
                    'image' => $this->productImage($product, $firstItem),
                    'search' => Str::lower($title.' '.$category.' '.$slug),
                ];
            })
            ->sortByDesc('sold')
            ->values()
            ->all();

        return $this->filterRows($rows, $search);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function filterRows(array $rows, string $search): array
    {
        if ($search === '') {
            return $rows;
        }

        $needle = Str::lower($search);

        return collect($rows)
            ->filter(fn (array $row): bool => str_contains((string) ($row['search'] ?? ''), $needle))
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>|null  $product
     * @return array<string, mixed>
     */
    private function bestProductSummary(?array $product, int $previousSold, string $period): array
    {
        if ($product === null) {
            return [
                'title' => 'No sales yet',
                'image' => asset('images/lovebyadelogo.png'),
                'sold' => '0 sold items',
                'trend' => '0.0% sold this '.$this->periods->noun($period),
            ];
        }

        return [
            'title' => $product['title'],
            'image' => $product['image'],
            'sold' => number_format((int) $product['sold']).' sold items',
            'trend' => $this->trend((int) $product['sold'], $previousSold).' sold this '.$this->periods->noun($period),
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return Collection<int, Order>
     */
    private function ordersBetween(Collection $orders, CarbonInterface $start, CarbonInterface $end): Collection
    {
        return $orders
            ->filter(fn (Order $order): bool => $this->periods->time($order->created_at)?->betweenIncluded($start, $end) === true)
            ->values();
    }

    /**
     * @param  Collection<int, User>  $customers
     * @return Collection<int, User>
     */
    private function customersBetween(Collection $customers, CarbonInterface $start, CarbonInterface $end): Collection
    {
        return $customers
            ->filter(fn (User $user): bool => $this->periods->time($user->created_at)?->betweenIncluded($start, $end) === true)
            ->values();
    }

    private function productImage(?Product $product, OrderItem $item): string
    {
        if ($product instanceof Product && $product->image_path !== null) {
            return Storage::disk('public')->url($product->image_path);
        }

        if ($product instanceof Product && $product->image_url !== null) {
            return $product->image_url;
        }

        return $item->product_image ?: asset('images/lovebyadelogo.png');
    }

    private function turnover(int $sold, int $stock): int
    {
        $total = $sold + $stock;

        return $total > 0 ? (int) round(($sold / $total) * 100) : 0;
    }

    private function trend(float|int $current, float|int $previous): string
    {
        if ((float) $previous <= 0.0) {
            return (float) $current > 0.0 ? '+100.0%' : '0.0%';
        }

        $change = (((float) $current - (float) $previous) / (float) $previous) * 100;

        return ($change > 0 ? '+' : '').number_format($change, 1).'%';
    }

    private function money(float $amount, int $decimals = 2): string
    {
        return number_format($amount, $decimals);
    }
}
