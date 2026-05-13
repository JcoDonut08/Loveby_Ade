<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(Request $request): View
    {
        $cart = $this->cart->summary($request);
        $deliveryFee = $cart['count'] > 0 ? 60.00 : 0.00;
        $discount = 0.00;
        $total = (float) $cart['subtotal'] + $deliveryFee - $discount;

        return view('pages.checkout', [
            'cart' => $cart,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'formattedDeliveryFee' => $this->formatPeso($deliveryFee),
            'formattedDiscount' => $this->formatPeso($discount),
            'formattedTotal' => $this->formatPeso($total),
        ]);
    }

    private function formatPeso(float $amount): string
    {
        return "\u{20B1}".number_format($amount, 2);
    }
}
