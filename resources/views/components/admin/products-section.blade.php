@props([
    'products',
    'categories',
])

@php
    $filters = collect([['key' => 'all', 'label' => 'All']])
        ->merge(collect($categories)->map(fn (string $category): array => ['key' => $category, 'label' => $category]))
        ->all();

    $activeCategory = request('category', 'all');
    $queryForCategory = function (string $category): array {
        $query = request()->except(['category', 'page']);

        if ($category !== 'all') {
            $query['category'] = $category;
        }

        return $query;
    };

    $imagePathsFor = function ($product): array {
        return collect($product->product_images ?: [])
            ->when($product->image_path, fn ($paths) => $paths->prepend($product->image_path))
            ->filter()
            ->unique()
            ->take(4)
            ->values()
            ->all();
    };

    $imageUrlFor = function (string $path): string {
        return Storage::disk('public')->url($path);
    };

    $imageFor = function ($product) use ($imagePathsFor, $imageUrlFor): string {
        $imagePaths = $imagePathsFor($product);

        if ($imagePaths !== []) {
            return $imageUrlFor($imagePaths[0]);
        }

        if ($product->image_path) {
            return Storage::disk('public')->url($product->image_path);
        }

        return $product->image_url ?: 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=900&q=80';
    };

    $stockMeta = function ($stock): array {
        if ($stock <= 0) {
            return ['label' => 'Out of stock', 'class' => 'border-rose-100 bg-rose-50 text-rose-500'];
        }

        if ($stock <= 10) {
            return ['label' => 'Low stock', 'class' => 'border-amber-100 bg-amber-50 text-[#7a4b21]'];
        }

        return ['label' => 'In stock', 'class' => 'border-emerald-100 bg-emerald-50 text-emerald-600'];
    };

    $showProductModal = $errors->any();
    $actionTooltip = 'pointer-events-none absolute bottom-full left-1/2 z-20 mb-2 min-w-max -translate-x-1/2 translate-y-1 rounded-lg bg-[#3b1728] px-2.5 py-1 text-xs font-extrabold text-white opacity-0 shadow-lg transition group-hover/action:translate-y-0 group-hover/action:opacity-100 group-focus-visible/action:translate-y-0 group-focus-visible/action:opacity-100';
@endphp

<section class="grid gap-5" data-admin-products data-backend-products="true">
    @if (session('status'))
        <div class="rounded-[1.25rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-extrabold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-4 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] sm:p-5">
        <div class="flex flex-col gap-4 2xl:flex-row 2xl:items-center 2xl:justify-between">
            <form class="relative w-full max-w-2xl" method="GET" action="{{ route('admin.products') }}" data-product-search-form>
                @if (request()->filled('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <label for="admin-product-search">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#9a6c7b]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="11" cy="11" r="6.5" />
                            <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                        </svg>
                    </span>
                    <input class="h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-12 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="admin-product-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search desserts..." data-product-search>
                </label>
            </form>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between 2xl:justify-end">
                <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Product category filters">
                    @foreach ($filters as $filter)
                        @php
                            $isActive = $activeCategory === $filter['key'] || ($filter['key'] === 'all' && ! request()->filled('category'));
                        @endphp
                        <a class="inline-flex h-10 shrink-0 items-center justify-center rounded-full px-4 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-love-pink-100 {{ $isActive ? 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]' : 'border border-love-pink-100 bg-love-cream text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500' }}" href="{{ route('admin.products', $queryForCategory($filter['key'])) }}" role="tab" aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                            {{ $filter['label'] }}
                        </a>
                    @endforeach
                </div>

                <button class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-product-server-open>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.75v12.5M5.75 12h12.5" />
                    </svg>
                    <span>Add product</span>
                </button>
            </div>
        </div>
    </div>

    @if ($products->count() > 0)
        <form id="admin-products-bulk-delete-form" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 px-4 py-3 shadow-[0_18px_45px_-38px_rgba(81,36,56,0.36)] sm:px-5" method="POST" action="{{ route('admin.products.bulk-destroy') }}" data-product-bulk-delete-form>
            @csrf
            @method('DELETE')

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="inline-flex h-11 items-center gap-3 rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] transition hover:border-love-pink-200 hover:bg-love-pink-100/70" for="admin-products-select-all">
                        <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-500 accent-love-pink-500 focus:ring-love-pink-200" id="admin-products-select-all" type="checkbox" data-product-bulk-select-all>
                        Select all shown
                    </label>

                    <div class="min-w-0">
                        <p class="text-sm font-extrabold text-[#512438]">Bulk actions</p>
                        <p class="mt-0.5 text-xs font-semibold text-[#9a6c7b]">Choose products from this page, then delete them together.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <p class="inline-flex h-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#9a6c7b]" data-product-bulk-count>No products selected</p>
                    <button class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-red-100 bg-red-50 px-4 text-sm font-extrabold text-red-600 opacity-45 transition hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-100 disabled:cursor-not-allowed disabled:hover:bg-red-50" type="submit" disabled data-product-bulk-submit>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                        </svg>
                        Delete selected
                    </button>
                </div>
            </div>
        </form>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4" data-product-results-grid>
        @forelse ($products as $product)
            @php
                $stock = $stockMeta($product->stock);
                $imagePaths = $imagePathsFor($product);
                $imagePayload = collect($imagePaths)->map(fn (string $path): array => [
                    'path' => $path,
                    'src' => $imageUrlFor($path),
                    'alt' => $product->title.' product photo',
                ])->all();
                $productPayload = e(json_encode([
                    'action' => route('admin.products.update', $product),
                    'title' => $product->title,
                    'description' => $product->description,
                    'category' => $product->category,
                    'price' => (float) $product->price,
                    'stock' => $product->stock,
                    'images' => $imagePayload,
                ]));
            @endphp

            <article class="overflow-hidden rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_70px_-46px_rgba(244,114,168,0.45)]" data-product-result>
                <div class="relative aspect-[4/3] overflow-hidden bg-[#fff4f7]">
                    <img class="h-full w-full object-cover transition duration-500 hover:scale-[1.04]" src="{{ $imageFor($product) }}" alt="{{ $product->title }}" loading="lazy">
                    <label class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/94 px-3 py-1.5 text-xs font-extrabold text-[#512438] shadow-sm backdrop-blur-sm transition hover:bg-love-cream" for="admin-product-select-{{ $product->getKey() }}">
                        <input class="h-4 w-4 rounded border-love-pink-200 text-love-pink-500 accent-love-pink-500 focus:ring-love-pink-200" id="admin-product-select-{{ $product->getKey() }}" type="checkbox" name="product_ids[]" value="{{ $product->getKey() }}" form="admin-products-bulk-delete-form" data-product-bulk-checkbox>
                        Select
                    </label>
                    <span class="absolute right-3 top-3 inline-flex items-center justify-center rounded-full border px-3 py-1 text-xs font-extrabold uppercase tracking-wide {{ $stock['class'] }}">
                        {{ $stock['label'] }}
                    </span>
                </div>

                <div class="grid min-h-60 gap-3 p-5">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-love-pink-500">{{ $product->category }}</p>
                        <h2 class="mt-2 line-clamp-2 min-h-[3rem] text-lg font-extrabold leading-6 text-[#3b1728]">{{ $product->title }}</h2>
                        <p class="mt-2 line-clamp-2 min-h-10 text-sm font-medium leading-5 text-[#9a6c7b]">{{ $product->description }}</p>
                    </div>

                    <div class="mt-auto flex items-end justify-between gap-4">
                        <div>
                            <p class="text-2xl font-extrabold tracking-tight text-love-pink-400">&#8369;{{ number_format((float) $product->price, 2) }}</p>
                            <p class="mt-1 text-sm font-medium text-[#9a6c7b]">{{ $product->stock }} in stock</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-love-pink-100 bg-love-cream text-[#512438] transition hover:-translate-y-0.5 hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Edit Product" data-product-edit data-product-payload='{!! $productPayload !!}'>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.75 17.75-.25 1.75 1.75-.25 10.9-10.9-1.5-1.5-10.9 10.9Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.75 7.75 1.5-1.5a1.5 1.5 0 0 1 2.12 0l.38.38a1.5 1.5 0 0 1 0 2.12l-1.5 1.5" />
                                </svg>
                                <span class="{{ $actionTooltip }}">Edit Product</span>
                            </button>

                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-product-delete-form>
                                @csrf
                                @method('DELETE')
                                <button class="group/action relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-500 transition hover:-translate-y-0.5 hover:bg-rose-100 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Delete Product">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                                    </svg>
                                    <span class="{{ $actionTooltip }}">Delete Product</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-white/92 p-8 text-center shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] sm:col-span-2 xl:col-span-3 2xl:col-span-4">
                <p class="text-base font-extrabold text-[#512438]">No desserts match this view.</p>
                <p class="mt-1 text-sm font-medium text-[#9a6c7b]">Try another category or search term.</p>
            </div>
        @endforelse
    </div>

    <nav class="flex flex-col gap-4 rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 px-5 py-4 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)] xl:flex-row xl:items-center xl:justify-between" aria-label="Product grid pagination">
        <form class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-5" method="GET" action="{{ route('admin.products') }}">
            @if (request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if (request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <label class="flex items-center gap-2 text-sm font-extrabold text-[#512438]" for="admin-products-page-size">
                <span>Products per page</span>
                <select class="h-10 rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-products-page-size" name="page_size" onchange="this.form.submit()">
                    @foreach ([4, 8, 12] as $size)
                        <option value="{{ $size }}" @selected((int) request('page_size', 8) === $size)>{{ $size }} products</option>
                    @endforeach
                </select>
            </label>

            <p class="text-sm font-medium text-[#9a6c7b]">
                Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
            </p>
        </form>

        <div class="flex flex-wrap items-center gap-2">
            @if ($products->onFirstPage())
                <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Previous</span>
            @else
                <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $products->previousPageUrl() }}">Previous</a>
            @endif

            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if ($page === $products->currentPage())
                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-love-pink-400 px-4 text-sm font-extrabold text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]">{{ $page }}</span>
                @else
                    <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-love-pink-100 bg-white px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($products->hasMorePages())
                <a class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" href="{{ $products->nextPageUrl() }}">Next</a>
            @else
                <span class="inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-extrabold text-[#d3a5b5]">Next</span>
            @endif
        </div>
    </nav>

    <div class="fixed inset-0 z-50 {{ $showProductModal ? 'flex' : 'hidden' }} items-center justify-center px-4 py-6" data-product-server-modal aria-hidden="{{ $showProductModal ? 'false' : 'true' }}">
        <button class="absolute inset-0 bg-[#3b1728]/35 backdrop-blur-sm" type="button" aria-label="Close product form" data-product-server-close></button>

        <section class="relative max-h-[calc(100vh-3rem)] w-full max-w-2xl overflow-y-auto rounded-[1.25rem] border border-love-pink-100 bg-white p-6 shadow-[0_30px_80px_-38px_rgba(59,23,40,0.55)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wide text-[#9a6c7b]">Product Details</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-[#3b1728]" data-product-modal-title>Add product</h2>
                </div>

                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Close product form" data-product-server-close>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />
                    </svg>
                </button>
            </div>

            <form class="mt-6 grid gap-5" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" data-product-server-form data-product-store-action="{{ route('admin.products.store') }}">
                @csrf
                <input type="hidden" name="_method" value="PATCH" data-product-form-method disabled>

                <label class="block" for="catalog-product-image">
                    <span class="text-sm font-extrabold text-[#512438]">Upload images</span>
                    <span class="mt-2 flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream px-4 py-6 text-center transition hover:border-love-pink-300 hover:bg-white">
                        <svg class="h-8 w-8 text-love-pink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.25V4.75M8.25 8.25 12 4.5l3.75 3.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 14.75v3.5h12.5v-3.5" />
                        </svg>
                        <span class="mt-3 text-sm font-extrabold text-[#512438]">Choose one or more product images</span>
                        <span class="mt-1 text-xs font-medium text-[#9a6c7b]">JPG, PNG, or WebP. Maximum of 4 images, 5 MB each.</span>
                    </span>
                    <input class="sr-only" id="catalog-product-image" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-product-images>
                    <div data-product-existing-images></div>
                    <div class="mt-3 grid gap-3 sm:grid-cols-4" data-product-image-preview>
                        <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-4 text-sm font-bold text-[#9a6c7b] sm:col-span-4">
                            No image selected yet.
                        </div>
                    </div>
                    @if ($errors->has('images') || $errors->has('images.*') || $errors->has('image'))
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $errors->first('images') ?: ($errors->first('images.*') ?: $errors->first('image')) }}</span>
                    @endif
                </label>

                <label class="block" for="catalog-product-name">
                    <span class="text-sm font-extrabold text-[#512438]">Product name</span>
                    <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-name" type="text" name="title" value="{{ old('title') }}" placeholder="Dessert name" required data-product-field="title">
                    @error('title')
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block" for="catalog-product-description">
                    <span class="text-sm font-extrabold text-[#512438]">Description</span>
                    <textarea class="mt-2 min-h-28 w-full resize-none rounded-[1.25rem] border border-love-pink-100 bg-love-cream px-4 py-3 text-sm font-medium text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-description" name="description" placeholder="Short product description" required data-product-field="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="mt-1 block text-xs font-bold text-rose-500">{{ $message }}</span>
                    @enderror
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block" for="catalog-product-category">
                        <span class="text-sm font-extrabold text-[#512438]">Category</span>
                        <select class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-category" name="category" required data-product-field="category">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block" for="catalog-product-price">
                        <span class="text-sm font-extrabold text-[#512438]">Price</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-price" type="number" name="price" min="1" step="0.01" value="{{ old('price') }}" required data-product-field="price">
                    </label>

                    <label class="block" for="catalog-product-stock">
                        <span class="text-sm font-extrabold text-[#512438]">Stock quantity</span>
                        <input class="mt-2 h-12 w-full rounded-full border border-love-pink-100 bg-love-cream px-4 text-sm font-extrabold text-[#512438] outline-none transition focus:border-love-pink-300 focus:bg-white focus:ring-4 focus:ring-love-pink-100/80" id="catalog-product-stock" type="number" name="stock" min="0" step="1" value="{{ old('stock', 0) }}" required data-product-field="stock">
                    </label>

                </div>

                @if ($errors->any())
                    <p class="text-sm font-bold text-rose-500">Please complete the product details.</p>
                @endif

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-full border border-love-pink-100 px-5 text-sm font-extrabold text-[#512438] transition hover:bg-love-pink-100 hover:text-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" data-product-server-close>
                        Cancel
                    </button>
                    <button class="inline-flex h-11 items-center justify-center rounded-full bg-love-pink-400 px-5 text-sm font-extrabold text-white shadow-[0_16px_34px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" data-product-save>
                        Save product
                    </button>
                </div>
            </form>
        </section>
    </div>
</section>
