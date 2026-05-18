<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\CustomerOrderService;
use App\Services\OrderReceiptService;
use App\Services\UserAuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private CustomerOrderService $orders,
        private OrderReceiptService $receipts,
        private UserAuditLogger $auditLogger,
    ) {}

    public function index(Request $request): View
    {
        $user = $this->authenticatedUser($request);

        return view('pages.orders.index', [
            'orders' => $this->orders->activeOrderCardsFor($user),
        ]);
    }

    public function deliveredProducts(Request $request): View
    {
        $user = $this->authenticatedUser($request);

        return view('pages.delivered_products', [
            'orders' => $this->orders->deliveredOrderCardsFor($user),
        ]);
    }

    public function confirmDelivery(Request $request, Order $order): RedirectResponse
    {
        $this->orders->confirmDelivery($order, $this->authenticatedUser($request));
        $this->auditLogger->record(
            $this->authenticatedUser($request),
            'Delivery Confirmed',
            'Orders',
            'Order '.$order->order_number.' was confirmed as delivered.',
            metadata: ['order_id' => $order->getKey()],
        );

        return redirect()
            ->route('orders.index')
            ->with('status', 'Order confirmed as delivered.');
    }

    public function confirmed(Request $request): View
    {
        $order = null;
        $lastOrderId = $request->session()->get('last_order_id');

        if ($lastOrderId && $request->user()) {
            $order = Order::query()
                ->whereBelongsTo($request->user())
                ->with('items')
                ->find($lastOrderId);
        }

        return view('pages.orders.confirmed', [
            'order' => $order,
        ]);
    }

    public function receipt(Request $request, Order $order): View|Response
    {
        $order = $this->receipts->forCustomer($order, $this->authenticatedUser($request));
        $viewData = $this->receipts->viewData(
            $order,
            route('orders.index'),
            'Back to orders',
            route('orders.receipt', ['order' => $order, 'download' => 1]),
        );

        if ($request->boolean('download')) {
            return Pdf::loadView('pages.orders.receipt_pdf', $viewData)
                ->setPaper('a4')
                ->download($this->receipts->downloadFilename($order));
        }

        return view('pages.orders.receipt', $viewData);
    }

    private function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
