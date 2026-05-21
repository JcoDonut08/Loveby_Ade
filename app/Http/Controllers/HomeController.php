<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Services\ProductCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private ProductCatalog $catalog) {}

    public function __invoke(Request $request): View
    {
        $today = now()->toDateString();

        return view('welcome', [
            'trendingProducts' => $this->catalog->trending(4),
            'recommendedProducts' => $this->catalog->recommendedFor($request->user(), 4),
            'activePromotions' => Promotion::query()
                ->where('is_active', true)
                ->where(fn ($query) => $query->whereNull('starts_at')->orWhereDate('starts_at', '<=', $today))
                ->where(fn ($query) => $query->whereNull('expires_at')->orWhereDate('expires_at', '>=', $today))
                ->latest()
                ->limit(6)
                ->get(),
        ]);
    }
}
