<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWalkInOrderRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Services\OrderReceiptService;
use App\Services\UserAuditLogger;
use App\Services\WalkInOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private WalkInOrderService $walkInOrders,
        private OrderReceiptService $receipts,
        private UserAuditLogger $auditLogger,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();
        $pageSize = (int) $request->integer('page_size', 10);
        $pageSize = in_array($pageSize, [5, 10, 20], true) ? $pageSize : 10;

        $orders = Order::query()
            ->with(['items.product', 'user'])
            ->when($status === 'walk_in', function ($query): void {
                $query->where('is_walk_in', true);
            })
            ->when(in_array($status, Order::statuses(), true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('order_number', 'like', '%'.$search.'%')
                        ->orWhere('full_name', 'like', '%'.$search.'%')
                        ->orWhere('email_address', 'like', '%'.$search.'%')
                        ->orWhereHas('items', function ($query) use ($search): void {
                            $query->where('product_title', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate($pageSize)
            ->withQueryString();

        $statusCounts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();
        $statusCounts['walk_in'] = Order::query()->where('is_walk_in', true)->count();

        return view('pages.admin.orders', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
            'statuses' => Order::statuses(),
            'products' => Product::query()
                ->where('is_active', true)
                ->orderBy('title')
                ->get(),
            'promotions' => Promotion::query()
                ->where('kind', Promotion::KIND_DISCOUNT)
                ->where('is_active', true)
                ->orderBy('code')
                ->get()
                ->filter(fn (Promotion $promotion): bool => $promotion->isAvailable())
                ->values(),
            'walkInOrderNumber' => $this->walkInOrders->uniqueOrderNumber(),
        ]);
    }

    public function store(StoreWalkInOrderRequest $request): RedirectResponse
    {
        $order = $this->walkInOrders->create($request->validated(), $request->user());
        $this->auditLogger->record(
            $request->user(),
            'Walk-in Order Created',
            'Orders',
            'Walk-in order '.$order->order_number.' was recorded.',
            metadata: ['order_id' => $order->getKey()],
        );

        return redirect()
            ->route('admin.orders', ['status' => 'walk_in'])
            ->with('status', 'Walk-in order added.');
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();
        $previousStatus = $order->status;

        if ($order->is_walk_in && ! in_array($validated['status'], [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED], true)) {
            throw ValidationException::withMessages([
                'status' => 'Walk-in orders can only be marked delivered or cancelled.',
            ]);
        }

        if (! $order->is_walk_in && $validated['status'] === Order::STATUS_DELIVERED) {
            throw ValidationException::withMessages([
                'status' => 'Online orders must be confirmed as delivered by the customer.',
            ]);
        }

        $order->update([
            'status' => $validated['status'],
            'cancellation_reason' => $validated['status'] === Order::STATUS_CANCELLED
                ? $validated['cancellation_reason']
                : null,
        ]);
        $this->auditLogger->record(
            $request->user(),
            'Order Status Updated',
            'Orders',
            'Order '.$order->order_number.' changed from '.str($previousStatus)->headline().' to '.str($order->status)->headline().'.',
            metadata: ['order_id' => $order->getKey()],
        );

        return redirect()
            ->route('admin.orders')
            ->with('status', 'Order status updated.');
    }

    public function receipt(Request $request, Order $order): View|Response
    {
        $order = $this->receipts->forAdmin($order);
        $viewData = $this->receipts->viewData(
            $order,
            route('admin.orders'),
            'Back to admin orders',
            route('admin.orders.receipt', ['order' => $order, 'download' => 1]),
        );

        if ($request->boolean('download')) {
            return Pdf::loadView('pages.orders.receipt_pdf', $viewData)
                ->setPaper('a4')
                ->download($this->receipts->downloadFilename($order));
        }

        return view('pages.orders.receipt', $viewData);
    }
}
