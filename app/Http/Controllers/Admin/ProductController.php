<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Product;
use App\Services\ProductCatalog;
use App\Services\ProductManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private ProductManagementService $products) {}

    public function index(Request $request): View|JsonResponse
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

        $viewData = [
            'products' => $products,
            'categories' => ProductCatalog::availableCategories(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('components.admin.products-section', $viewData)->render(),
            ]);
        }

        return view('pages.admin.products', $viewData);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->products->create($request->validated(), $this->productImages($request));

        return redirect()
            ->route('admin.products')
            ->with('status', 'Product added to the catalog.');
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $this->products->update(
            $product,
            $request->validated(),
            $this->productImages($request),
            $this->existingProductImages($request, $product),
        );

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

    /**
     * @return array<int, UploadedFile>
     */
    private function productImages(StoreProductRequest $request): array
    {
        $images = $request->file('images');

        if (is_array($images)) {
            return array_values($images);
        }

        $legacyImage = $request->file('image');

        return $legacyImage === null ? [] : [$legacyImage];
    }

    /**
     * @return array<int, string>|null
     */
    private function existingProductImages(StoreProductRequest $request, Product $product): ?array
    {
        if (! $request->has('existing_images')) {
            return null;
        }

        $allowedPaths = collect($product->product_images ?: [])
            ->when($product->image_path, fn ($paths) => $paths->prepend($product->image_path))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return collect($request->input('existing_images', []))
            ->filter(fn (mixed $path): bool => is_string($path) && in_array($path, $allowedPaths, true))
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }
}
