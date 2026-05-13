<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Product;
use App\Services\ProductCatalog;
use App\Services\ProductManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private ProductManagementService $products) {}

    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();
        $pageSize = (int) $request->integer('page_size', 8);
        $pageSize = in_array($pageSize, [4, 8, 12], true) ? $pageSize : 8;

        $products = Product::query()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($category, ProductCatalog::availableCategories(), true), function ($query) use ($category): void {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate($pageSize)
            ->withQueryString();

        return view('pages.admin.products', [
            'products' => $products,
            'categories' => ProductCatalog::availableCategories(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->products->create($request->validated(), $request->file('image'));

        return redirect()
            ->route('admin.products')
            ->with('status', 'Product added to the catalog.');
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $this->products->update($product, $request->validated(), $request->file('image'));

        return redirect()
            ->route('admin.products')
            ->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->products->delete($product);

        return redirect()
            ->route('admin.products')
            ->with('status', 'Product removed from the catalog.');
    }
}
