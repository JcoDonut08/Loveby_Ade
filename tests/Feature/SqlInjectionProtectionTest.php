<?php

use App\Models\Order;
use App\Models\Product;
use PHPUnit\Framework\TestCase;

test('public product search treats sql payloads as plain text', function () {
    skipSqlInjectionDatabaseTestIfNeeded($this);

    Product::factory()->create([
        'title' => 'Ube Cloud Cake',
        'category' => 'Cakes',
    ]);
    Product::factory()->create([
        'title' => 'Chocolate Chip Cookies',
        'category' => 'Cookies',
    ]);

    $this->get(route('products.index', ['search' => "' OR 1=1 --"]))
        ->assertSuccessful()
        ->assertDontSee('Ube Cloud Cake')
        ->assertDontSee('Chocolate Chip Cookies');
});

test('admin product search treats sql payloads as plain text', function () {
    skipSqlInjectionDatabaseTestIfNeeded($this);

    Product::factory()->create([
        'title' => 'Ube Cloud Cake',
        'category' => 'Cakes',
    ]);
    Product::factory()->create([
        'title' => 'Chocolate Chip Cookies',
        'category' => 'Cookies',
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.products', ['search' => "' OR 1=1 --"]))
        ->assertSuccessful()
        ->assertDontSee('Ube Cloud Cake')
        ->assertDontSee('Chocolate Chip Cookies');
});

test('admin order search treats sql payloads as plain text', function () {
    skipSqlInjectionDatabaseTestIfNeeded($this);

    $firstOrder = Order::factory()->create([
        'order_number' => 'LBA-410001',
        'full_name' => 'Mia Reyes',
        'email_address' => 'mia@example.com',
    ]);
    $firstOrder->items()->create([
        'product_slug' => 'ube-cloud-cake',
        'product_title' => 'Ube Cloud Cake',
        'category' => 'Cakes',
        'product_image' => 'https://example.com/ube.jpg',
        'unit_price' => 180,
        'quantity' => 1,
        'line_total' => 180,
    ]);
    $secondOrder = Order::factory()->create([
        'order_number' => 'LBA-410002',
        'full_name' => 'Luna Santos',
        'email_address' => 'luna@example.com',
    ]);
    $secondOrder->items()->create([
        'product_slug' => 'chocolate-chip-cookies',
        'product_title' => 'Chocolate Chip Cookies',
        'category' => 'Cookies',
        'product_image' => 'https://example.com/cookies.jpg',
        'unit_price' => 90,
        'quantity' => 1,
        'line_total' => 90,
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.orders', ['search' => "' OR 1=1 --"]))
        ->assertSuccessful()
        ->assertDontSee('LBA-410001')
        ->assertDontSee('Mia Reyes')
        ->assertDontSee('Ube Cloud Cake')
        ->assertDontSee('LBA-410002')
        ->assertDontSee('Luna Santos')
        ->assertDontSee('Chocolate Chip Cookies');
});

test('application code avoids dangerous raw sql entrypoints', function () {
    $violations = [];
    $unsafePatterns = [
        '/\bDB::\s*(?:select|selectOne|scalar|cursor|insert|update|delete|statement|affectingStatement|unprepared|raw)\s*\(/' => 'DB facade raw SQL execution',
        '/->\s*(?:whereRaw|orWhereRaw|havingRaw|orHavingRaw|orderByRaw|groupByRaw|fromRaw|joinRaw|crossJoinRaw)\s*\(/' => 'raw query builder clause',
        '/->\s*getPdo\s*\(/' => 'direct PDO access',
    ];

    foreach (sqlInjectionPhpFilesIn([app_path(), base_path('routes')]) as $path => $contents) {
        foreach ($unsafePatterns as $pattern => $description) {
            if (preg_match($pattern, $contents) === 1) {
                $violations[] = "{$path}: {$description}";
            }
        }

        preg_match_all('/->\s*selectRaw\s*\((.*?)\)/s', $contents, $matches);

        foreach ($matches[1] as $rawArguments) {
            if (str_contains($rawArguments, '$') || str_contains($rawArguments, '{')) {
                $violations[] = "{$path}: selectRaw must use reviewed literal SQL only";
            }
        }
    }

    expect($violations)->toBeEmpty();
});

function skipSqlInjectionDatabaseTestIfNeeded(TestCase $testCase): void
{
    if (config('database.default') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
        $testCase->markTestSkipped('PDO SQLite is required for in-memory feature database tests.');
    }
}

/**
 * @param  array<int, string>  $directories
 * @return array<string, string>
 */
function sqlInjectionPhpFilesIn(array $directories): array
{
    $files = [];

    foreach ($directories as $directory) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = str($file->getPathname())
                ->after(base_path(DIRECTORY_SEPARATOR))
                ->replace('\\', '/')
                ->toString();

            $files[$path] = (string) file_get_contents($file->getPathname());
        }
    }

    return $files;
}
