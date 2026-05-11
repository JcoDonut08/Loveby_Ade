<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterProductsRequest;
use App\Services\ProductCatalog;
use App\Services\SearchAssistant;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductCatalog $catalog,
        private SearchAssistant $searchAssistant,
    ) {}

    public function index(FilterProductsRequest $request): View
    {
        $filters = $request->validated();
        $this->searchAssistant->remember($request, $filters['search'] ?? null);

        return view('pages.products.index', [
            'categories' => $this->catalog->categories(),
            'filters' => $filters,
            'priceRange' => $this->catalog->priceRange(),
            'products' => $this->catalog->filter($filters),
        ]);
    }

    public function showDefault(): View
    {
        return $this->show('pastel-donut-box');
    }

    public function show(string $slug): View
    {
        $product = $this->catalog->find($slug);

        abort_if($product === null, 404);

        return view('pages.products.show', [
            'product' => $product,
            'recommendations' => $this->catalog->recommendations($slug),
        ]);
    }
}
