<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(Request $request): View
    {
        return view('pages.cart', [
            'cart' => $this->cart->summary($request),
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        return response()->json($this->jsonSummary($request));
    }

    public function store(AddCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->cart->add(
            $request,
            $validated['slug'],
            (int) ($validated['quantity'] ?? 1),
        );

        return response()->json($this->jsonSummary($request));
    }

    public function update(UpdateCartItemRequest $request, string $slug): JsonResponse
    {
        $this->cart->update($request, $slug, (int) $request->validated()['quantity']);

        return response()->json($this->jsonSummary($request));
    }

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $this->cart->remove($request, $slug);

        return response()->json($this->jsonSummary($request));
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonSummary(Request $request): array
    {
        $summary = $this->cart->summary($request);

        return [
            'count' => $summary['count'],
            'subtotal' => $summary['subtotal'],
            'formatted_subtotal' => $summary['formatted_subtotal'],
            'items' => $summary['items']->values(),
        ];
    }
}
