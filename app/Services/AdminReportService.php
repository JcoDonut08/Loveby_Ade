<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminReportService
{
    public const REPORT_SALES = 'sales';

    public const REPORT_PRODUCTS = 'products';

    public const FORMAT_PDF = 'pdf';

    public const FORMAT_EXCEL = 'excel';

    public function __construct(private OrderAccountingService $accounting) {}

    /**
     * @return array<int, string>
     */
    public static function reportTypes(): array
    {
        return [self::REPORT_SALES, self::REPORT_PRODUCTS];
    }

    /**
     * @return array<int, string>
     */
    public static function formats(): array
    {
        return [self::FORMAT_PDF, self::FORMAT_EXCEL];
    }

    /**
     * @param  array{search?: string|null, from?: string|null, to?: string|null}  $filters
     * @return array<string, mixed>
     */
    public function overview(array $filters): array
    {
        $normalized = $this->filters($filters);

        return [
            'filters' => $normalized['inputs'],
            'rangeLabel' => $normalized['label'],
            'generatedAt' => Carbon::now($this->timezone())->format('F j, Y, g:i A'),
        ];
    }

    /**
     * @param  array{search?: string|null, from?: string|null, to?: string|null}  $filters
     * @return array<string, mixed>
     */
    public function report(string $type, array $filters): array
    {
        return match ($type) {
            self::REPORT_PRODUCTS => $this->productPerformanceReport($filters),
            default => $this->salesReport($filters),
        };
    }

    /**
     * @param  array<string, mixed>  $report
     */
    public function filename(array $report, string $format): string
    {
        $extension = $format === self::FORMAT_PDF ? 'pdf' : 'xls';
        $dateRange = Str::of((string) $report['range_label'])
            ->replaceMatches('/[^A-Za-z0-9]+/', '-')
            ->trim('-')
            ->lower();

        return 'loveby-ade-'.Str::slug((string) $report['title']).'-'.$dateRange.'.'.$extension;
    }

    /**
     * @param  array{search?: string|null, from?: string|null, to?: string|null}  $filters
     * @return array<string, mixed>
     */
    private function salesReport(array $filters): array
    {
        $normalized = $this->filters($filters);
        $orders = $this->orders($normalized, true);
        $paidOrders = $orders->filter(fn (Order $order): bool => $this->accounting->countsAsPaid($order));
        $reportableOrders = $orders->reject(fn (Order $order): bool => $order->status === Order::STATUS_CANCELLED);
        $revenue = $paidOrders->sum(fn (Order $order): float => (float) $order->total);

        return $this->baseReport('Sales report', 'Revenue, orders and AOV across periods', $normalized, [
            ['label' => 'Paid revenue', 'value' => $this->money($revenue)],
            ['label' => 'Reportable orders', 'value' => number_format($reportableOrders->count())],
            ['label' => 'Items sold', 'value' => number_format($reportableOrders->sum(fn (Order $order): int => (int) $order->items->sum('quantity')))],
            ['label' => 'Average order value', 'value' => $this->money($paidOrders->count() > 0 ? $revenue / $paidOrders->count() : 0)],
        ], [
            ['key' => 'order_number', 'label' => 'Order #', 'type' => 'text', 'width' => 18],
            ['key' => 'date', 'label' => 'Date', 'type' => 'text', 'width' => 18],
            ['key' => 'customer', 'label' => 'Customer', 'type' => 'text', 'width' => 26],
            ['key' => 'products', 'label' => 'Products', 'type' => 'text', 'width' => 42],
            ['key' => 'status', 'label' => 'Status', 'type' => 'text', 'width' => 18],
            ['key' => 'payment_method', 'label' => 'Payment', 'type' => 'text', 'width' => 20],
            ['key' => 'items', 'label' => 'Items', 'type' => 'number', 'width' => 10],
            ['key' => 'subtotal', 'label' => 'Subtotal', 'type' => 'money', 'width' => 15],
            ['key' => 'discount', 'label' => 'Discount', 'type' => 'money', 'width' => 15],
            ['key' => 'total', 'label' => 'Total', 'type' => 'money', 'width' => 15],
            ['key' => 'payment_status', 'label' => 'Paid?', 'type' => 'text', 'width' => 12],
        ], $orders->map(fn (Order $order): array => [
            'order_number' => $order->order_number,
            'date' => $order->created_at?->timezone($this->timezone())->format('Y-m-d g:i A') ?? '',
            'customer' => $order->full_name ?: ($order->user?->name ?? 'Customer'),
            'products' => $order->items->map(fn ($item): string => $item->product_title.' x'.$item->quantity)->implode('; '),
            'status' => Str::of($order->status)->replace('_', ' ')->title()->toString(),
            'payment_method' => $order->payment_method,
            'items' => (int) $order->items->sum('quantity'),
            'subtotal' => (float) $order->subtotal,
            'discount' => (float) $order->discount,
            'total' => (float) $order->total,
            'payment_status' => $this->accounting->countsAsPaid($order) ? 'Paid' : 'Pending',
        ])->values()->all());
    }

    /**
     * @param  array{search?: string|null, from?: string|null, to?: string|null}  $filters
     * @return array<string, mixed>
     */
    private function productPerformanceReport(array $filters): array
    {
        $normalized = $this->filters($filters);
        $orders = $this->orders($normalized, false);
        $itemsByProduct = $orders->flatMap->items->groupBy(fn ($item): string => (string) ($item->product_id ?: $item->product_slug));
        $products = Product::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        $rows = $products->map(function (Product $product) use ($itemsByProduct): array {
            $items = $itemsByProduct->get((string) $product->id, collect());
            $unitsSold = (int) $items->sum('quantity');
            $revenue = (float) $items->sum('line_total');

            return [
                'product' => $product->title,
                'category' => $product->category,
                'units_sold' => $unitsSold,
                'gross_sales' => $revenue,
                'stock' => (int) $product->stock,
                'turnover' => $this->turnover($unitsSold, (int) $product->stock),
                'current_price' => (float) $product->price,
                'last_sold' => $items->max(fn ($item): string => $item->order?->created_at?->timezone($this->timezone())->format('Y-m-d g:i A') ?? '') ?: 'No sales',
            ];
        })->filter(fn (array $row): bool => $this->matchesSearch($row, $normalized['search']))->sortByDesc('units_sold')->values();

        return $this->baseReport('Product performance', 'Top-selling desserts and stock turnover', $normalized, [
            ['label' => 'Gross product sales', 'value' => $this->money((float) $rows->sum('gross_sales'))],
            ['label' => 'Units sold', 'value' => number_format((int) $rows->sum('units_sold'))],
            ['label' => 'Active products', 'value' => number_format($rows->count())],
            ['label' => 'Low stock items', 'value' => number_format($rows->filter(fn (array $row): bool => (int) $row['stock'] <= 10)->count())],
        ], [
            ['key' => 'product', 'label' => 'Product', 'type' => 'text', 'width' => 30],
            ['key' => 'category', 'label' => 'Category', 'type' => 'text', 'width' => 20],
            ['key' => 'units_sold', 'label' => 'Units Sold', 'type' => 'number', 'width' => 14],
            ['key' => 'gross_sales', 'label' => 'Gross Sales', 'type' => 'money', 'width' => 16],
            ['key' => 'stock', 'label' => 'Stock', 'type' => 'number', 'width' => 12],
            ['key' => 'turnover', 'label' => 'Turnover %', 'type' => 'percent', 'width' => 14],
            ['key' => 'current_price', 'label' => 'Current Price', 'type' => 'money', 'width' => 16],
            ['key' => 'last_sold', 'label' => 'Last Sold', 'type' => 'text', 'width' => 20],
        ], $rows->all());
    }

    /**
     * @param  array<string, mixed>  $normalized
     * @return Collection<int, Order>
     */
    private function orders(array $normalized, bool $includeCancelled): Collection
    {
        return Order::query()
            ->with(['items.product', 'user'])
            ->whereBetween('created_at', [$normalized['start'], $normalized['end']])
            ->when(! $includeCancelled, fn (Builder $query) => $query->where('status', '!=', Order::STATUS_CANCELLED))
            ->when($normalized['search'] !== '', fn (Builder $query) => $this->applyOrderSearch($query, $normalized['search']))
            ->latest()
            ->get();
    }

    private function applyOrderSearch(Builder $query, string $search): void
    {
        $query->where(function (Builder $query) use ($search): void {
            $query->where('order_number', 'like', '%'.$search.'%')
                ->orWhere('full_name', 'like', '%'.$search.'%')
                ->orWhere('email_address', 'like', '%'.$search.'%')
                ->orWhereHas('user', fn (Builder $query) => $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%'))
                ->orWhereHas('items', fn (Builder $query) => $query->where('product_title', 'like', '%'.$search.'%')->orWhere('category', 'like', '%'.$search.'%'));
        });
    }

    /**
     * @param  array<string, mixed>  $normalized
     * @param  array<int, array{label: string, value: string}>  $summary
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<string, mixed>
     */
    private function baseReport(string $title, string $subtitle, array $normalized, array $summary, array $columns, array $rows): array
    {
        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'range_label' => $normalized['label'],
            'generated_at' => Carbon::now($this->timezone())->format('F j, Y, g:i A'),
            'search' => $normalized['search'],
            'summary' => $summary,
            'columns' => $columns,
            'rows' => $rows,
        ];
    }

    /**
     * @param  array{search?: string|null, from?: string|null, to?: string|null}  $filters
     * @return array{search: string, start: Carbon, end: Carbon, label: string, inputs: array{search: string, from: string, to: string}}
     */
    private function filters(array $filters): array
    {
        $timezone = $this->timezone();
        $search = trim((string) ($filters['search'] ?? ''));
        $to = filled($filters['to'] ?? null)
            ? Carbon::createFromFormat('Y-m-d', (string) $filters['to'], $timezone)->endOfDay()
            : Carbon::now($timezone)->endOfDay();
        $from = filled($filters['from'] ?? null)
            ? Carbon::createFromFormat('Y-m-d', (string) $filters['from'], $timezone)->startOfDay()
            : $to->copy()->subDays(29)->startOfDay();

        if ($from->greaterThan($to)) {
            $to = $from->copy()->endOfDay();
        }

        return [
            'search' => $search,
            'start' => $from,
            'end' => $to,
            'label' => $from->format('M j, Y').' to '.$to->format('M j, Y'),
            'inputs' => [
                'search' => $search,
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
            ],
        ];
    }

    private function matchesSearch(array $row, string $search): bool
    {
        if ($search === '') {
            return true;
        }

        return str_contains(Str::lower(implode(' ', $row)), Str::lower($search));
    }

    private function turnover(int $sold, int $stock): float
    {
        $available = $sold + $stock;

        return $available > 0 ? round(($sold / $available) * 100, 1) : 0.0;
    }

    private function money(float $amount): string
    {
        return 'PHP '.number_format($amount, 2);
    }

    private function timezone(): string
    {
        return (string) config('app.business_timezone', config('app.timezone', 'UTC'));
    }
}
