<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Promotion;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PromotionService;
use App\Services\UserAuditLogger;
use Illuminate\Http\JsonResponse;
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
        $promo = $this->promotionPreview($request, $request->query('promo_code'));

        return view('pages.checkout', [
            'cart' => $promo['cart'],
            'deliveryFee' => $promo['deliveryFee'],
            'discount' => $promo['discount'],
            'total' => $promo['total'],
            'promoCode' => $promo['promoCode'],
            'appliedPromotion' => $promo['appliedPromotion'],
            'promoError' => $promo['promoError'],
            'formattedDeliveryFee' => 'Free',
            'formattedDiscount' => $promo['formattedDiscount'],
            'formattedTotal' => $promo['formattedTotal'],
        ]);
    }

    public function promotion(Request $request): JsonResponse
    {
        $promo = $this->promotionPreview($request, $request->query('promo_code'));

        return response()->json([
            'applied' => $promo['appliedPromotion'] !== null
                ? [
                    'code' => $promo['appliedPromotion']->code,
                    'message' => $promo['appliedPromotion']->code.' applied.',
                ]
                : null,
            'discount' => $promo['discount'],
            'error' => $promo['promoError'],
            'formattedDiscount' => $promo['formattedDiscount'],
            'formattedTotal' => $promo['formattedTotal'],
            'promoCode' => $promo['promoCode'],
            'total' => $promo['total'],
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

    /**
     * @return array{
     *     cart: array<string, mixed>,
     *     deliveryFee: float,
     *     discount: float,
     *     total: float,
     *     promoCode: string|null,
     *     appliedPromotion: Promotion|null,
     *     promoError: string|null,
     *     formattedDiscount: string,
     *     formattedTotal: string
     * }
     */
    private function promotionPreview(Request $request, mixed $code): array
    {
        $cart = $this->cart->summary($request);
        $deliveryFee = CheckoutService::DELIVERY_FEE;
        $promoCode = $this->promotions->normalizeCode(is_string($code) ? $code : null);
        $appliedPromotion = $this->promotions->findAvailable($promoCode);
        $discount = $appliedPromotion?->discountFor((float) $cart['subtotal']) ?? 0.00;
        $total = (float) $cart['subtotal'] + $deliveryFee - $discount;

        return [
            'cart' => $cart,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'promoCode' => $promoCode,
            'appliedPromotion' => $appliedPromotion,
            'promoError' => $promoCode !== null && $appliedPromotion === null ? 'Promo code is not active or does not exist.' : null,
            'formattedDiscount' => $this->promotions->formatPeso($discount),
            'formattedTotal' => $this->promotions->formatPeso($total),
        ];
    }
}
