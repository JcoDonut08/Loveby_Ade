<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PromotionService;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout,
        private PromotionService $promotions,
        private UserAuditLogger $auditLogger,
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->cart->summary($request);
        $deliveryFee = CheckoutService::DELIVERY_FEE;
        $promoCode = $this->promotions->normalizeCode($request->query('promo_code'));
        $appliedPromotion = $this->promotions->findAvailable($promoCode);
        $discount = $appliedPromotion?->discountFor((float) $cart['subtotal']) ?? 0.00;
        $total = (float) $cart['subtotal'] + $deliveryFee - $discount;

        return view('pages.checkout', [
            'cart' => $cart,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'promoCode' => $promoCode,
            'appliedPromotion' => $appliedPromotion,
            'promoError' => $promoCode !== null && $appliedPromotion === null ? 'Promo code is not active or does not exist.' : null,
            'formattedDeliveryFee' => 'Free',
            'formattedDiscount' => $this->promotions->formatPeso($discount),
            'formattedTotal' => $this->promotions->formatPeso($total),
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->checkout->createOrder($request, $request->validated());
        $this->auditLogger->record(
            $request->user(),
            'Order Placed',
            'Orders',
            'Order '.$order->order_number.' was placed.',
            metadata: ['order_id' => $order->getKey()],
        );

        return redirect()
            ->route('orders.confirmed')
            ->with('last_order_id', $order->id);
    }
}
