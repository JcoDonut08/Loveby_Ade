<?php

test('admin reports page renders report generation cards', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.reports'))
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
        ->assertSee('href="'.route('admin.reports').'" aria-current="page"', false);
});
