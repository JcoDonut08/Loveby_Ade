<?php

namespace App\Http\Controllers;

use App\Services\ProductCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private ProductCatalog $catalog) {}

    public function __invoke(Request $request): View
    {
        return view('welcome', [
            'trendingProducts' => $this->catalog->trending(4),
            'recommendedProducts' => $this->catalog->recommendedFor($request->user(), 4),
        ]);
    }
}
