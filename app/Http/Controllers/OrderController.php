<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\CustomerOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private CustomerOrderService $orders) {}

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

    private function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
