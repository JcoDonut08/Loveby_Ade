<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\AdminReportPdfViewData;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

test('admin reports page renders report generation cards', function () {
    skipReportsDatabaseTestIfNeeded($this);

    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-22 10:00:00', 'UTC'));

    $admin = adminUser();

    try {
        $this->actingAs($admin)
            ->get(route('admin.reports', [
                'from' => '2026-05-01',
                'to' => '2026-05-22',
                'search' => 'Ube',
            ]))
            ->assertSuccessful()
            ->assertSee('Reports')
            ->assertSee('Export and share business reports.')
            ->assertSee($admin->name)
            ->assertSee('Generate reports')
            ->assertSee('Pick a date range and download in your favorite format')
            ->assertSee('From')
            ->assertSee('To')
            ->assertSee('Sales report')
            ->assertSee('Revenue, orders and AOV across periods')
            ->assertSee('Product performance')
            ->assertSee('Top-selling desserts and stock turnover')
            ->assertSee('PDF')
            ->assertSee('Excel')
            ->assertSee('Download')
            ->assertSee('data-admin-reports', false)
            ->assertSee('id="admin-report-filter-form"', false)
            ->assertSee('name="search"', false)
            ->assertSee('value="Ube"', false)
            ->assertSee('name="from"', false)
            ->assertSee('value="2026-05-01"', false)
            ->assertSee('name="to"', false)
            ->assertSee('value="2026-05-22"', false)
            ->assertSee('name="sales_format"', false)
            ->assertSee('name="products_format"', false)
            ->assertSee('type="hidden"', false)
            ->assertSee('value="pdf"', false)
            ->assertSee('value="excel"', false)
            ->assertSee('data-report-format-button', false)
            ->assertSee('data-report-download="sales"', false)
            ->assertSee('data-report-download="products"', false)
            ->assertSee('formaction="'.route('admin.reports.export', ['report' => 'sales']).'"', false)
            ->assertSee('formaction="'.route('admin.reports.export', ['report' => 'products']).'"', false)
            ->assertDontSee('formaction="'.route('admin.reports.export', ['report' => 'sales', 'format' => 'pdf']).'"', false)
            ->assertDontSee('formaction="'.route('admin.reports.export', ['report' => 'products', 'format' => 'excel']).'"', false)
            ->assertSee('href="'.route('admin.reports').'" aria-current="page"', false);
    } finally {
        Carbon::setTestNow();
    }
});

test('admin can download an aesthetic sales pdf report from real order data', function () {
    skipReportsDatabaseTestIfNeeded($this);

    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-22 10:00:00', 'UTC'));

    try {
        $admin = adminUser();
        $customer = User::factory()->create(['name' => 'Mia Reyes']);
        $product = Product::factory()->create([
            'title' => 'Ube Cloud Cake',
            'slug' => 'ube-cloud-cake',
            'category' => 'Cakes',
            'price' => 180,
        ]);

        createReportOrder($customer, $product, [
            'order_number' => 'LBA-620001',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'GCash',
            'quantity' => 2,
            'line_total' => 360,
            'total' => 360,
            'created_at' => Carbon::parse('2026-05-20 09:00:00', 'UTC'),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export', [
                'report' => 'sales',
                'from' => '2026-05-01',
                'to' => '2026-05-22',
                'search' => 'Ube',
                'sales_format' => 'pdf',
            ]));

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/pdf');

        expect($response->headers->get('content-disposition'))
            ->toContain('attachment; filename=loveby-ade-sales-report')
            ->and($response->getContent())->toStartWith('%PDF');
    } finally {
        Carbon::setTestNow();
    }
});

test('admin report pdf view includes the logo and polished report sections', function () {
    $report = [
        'title' => 'Sales report',
        'subtitle' => 'Revenue, orders and AOV across periods',
        'range_label' => 'May 1, 2026 to May 22, 2026',
        'generated_at' => 'May 22, 2026, 10:00 AM',
        'search' => 'Ube',
        'summary' => [
            ['label' => 'Paid revenue', 'value' => 'PHP 360.00'],
            ['label' => 'Reportable orders', 'value' => '1'],
            ['label' => 'Items sold', 'value' => '2'],
            ['label' => 'Average order value', 'value' => 'PHP 360.00'],
        ],
        'columns' => [
            ['key' => 'order_number', 'label' => 'Order #', 'type' => 'text'],
            ['key' => 'total', 'label' => 'Total', 'type' => 'money'],
        ],
        'rows' => [
            ['order_number' => 'LBA-620001', 'total' => 360],
        ],
    ];

    $viewData = app(AdminReportPdfViewData::class)->forReport($report);
    $html = view('pages.admin.reports_pdf', $viewData)->render();

    expect($viewData['logoDataUri'])->toStartWith('data:image/jpeg;base64,')
        ->and($viewData['rowCount'])->toBe(1)
        ->and($html)->toContain('Loveby_Ade logo')
        ->and($html)->toContain('Executive summary')
        ->and($html)->toContain('Detailed records')
        ->and($html)->toContain('Report details')
        ->and($html)->toContain('Page <span class="page-number"></span> of <span class="page-count"></span>');
});

test('admin can download a clean excel product performance report from real product data', function () {
    skipReportsDatabaseTestIfNeeded($this);

    config(['app.business_timezone' => 'UTC']);
    Carbon::setTestNow(Carbon::parse('2026-05-22 10:00:00', 'UTC'));

    try {
        $admin = adminUser();
        $customer = User::factory()->create(['name' => 'Mia Reyes']);
        $product = Product::factory()->create([
            'title' => 'Ube Cloud Cake',
            'slug' => 'ube-cloud-cake',
            'category' => 'Cakes',
            'price' => 180,
            'stock' => 8,
        ]);
        Product::factory()->create([
            'title' => 'Chocolate Cookies',
            'slug' => 'chocolate-cookies',
            'category' => 'Cookies',
            'stock' => 11,
        ]);

        createReportOrder($customer, $product, [
            'order_number' => 'LBA-620001',
            'status' => Order::STATUS_DELIVERED,
            'payment_method' => 'GCash',
            'quantity' => 3,
            'line_total' => 540,
            'total' => 540,
            'created_at' => Carbon::parse('2026-05-20 09:00:00', 'UTC'),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export', [
                'report' => 'products',
                'from' => '2026-05-01',
                'to' => '2026-05-22',
                'search' => 'Ube',
                'products_format' => 'excel',
            ]));

        $response->assertSuccessful();

        $content = $response->streamedContent();

        expect($response->headers->get('content-type'))->toContain('application/vnd.ms-excel')
            ->and($response->headers->get('content-disposition'))->toContain('attachment; filename=loveby-ade-product-performance')
            ->and($content)->toContain('<?mso-application progid="Excel.Sheet"?>')
            ->and($content)->toContain('Product performance')
            ->and($content)->toContain('Ube Cloud Cake')
            ->and($content)->not->toContain('Chocolate Cookies');
    } finally {
        Carbon::setTestNow();
    }
});

test('admin must select a report format before downloading', function () {
    skipReportsDatabaseTestIfNeeded($this);

    $admin = adminUser();

    $this->actingAs($admin)
        ->from(route('admin.reports'))
        ->get(route('admin.reports.export', ['report' => 'sales']))
        ->assertRedirect(route('admin.reports'))
        ->assertSessionHasErrors('format');
});

/**
 * @param  array{order_number: string, status: string, payment_method: string, quantity: int, line_total: int|float, total: int|float, created_at: Carbon}  $data
 */
function createReportOrder(User $customer, Product $product, array $data): Order
{
    $order = Order::factory()
        ->for($customer)
        ->create([
            'order_number' => $data['order_number'],
            'status' => $data['status'],
            'full_name' => $customer->name,
            'email_address' => $customer->email,
            'payment_method' => $data['payment_method'],
            'subtotal' => $data['total'],
            'delivery_fee' => 0,
            'discount' => 0,
            'total' => $data['total'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['created_at'],
        ]);

    OrderItem::factory()
        ->for($order)
        ->for($product)
        ->create([
            'product_slug' => $product->slug,
            'product_title' => $product->title,
            'category' => $product->category,
            'unit_price' => $product->price,
            'quantity' => $data['quantity'],
            'line_total' => $data['line_total'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['created_at'],
        ]);

    return $order;
}

function skipReportsDatabaseTestIfNeeded(TestCase $testCase): void
{
    if (config('database.default') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
        $testCase->markTestSkipped('PDO SQLite is required for in-memory feature database tests.');
    }
}
