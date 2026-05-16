<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout,
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->cart->summary($request);
        $deliveryFee = CheckoutService::DELIVERY_FEE;
        $discount = 0.00;
        $total = (float) $cart['subtotal'] + $deliveryFee - $discount;

        return view('pages.checkout', [
            'cart' => $cart,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'formattedDeliveryFee' => 'Free',
            'formattedDiscount' => $this->formatPeso($discount),
            'formattedTotal' => $this->formatPeso($total),
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->checkout->createOrder($request, $request->validated());

        return redirect()
            ->route('orders.confirmed')
            ->with('last_order_id', $order->id);
    }

    private function formatPeso(float $amount): string
    {
        return "\u{20B1}".number_format($amount, 2);
    }
}
