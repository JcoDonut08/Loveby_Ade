<?php

test('admin dashboard renders the redesigned overview sections', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Dashboard')
        ->assertSee('Revenue')
        ->assertSee('Orders')
        ->assertSee('Pending')
        ->assertSee('Customers')
        ->assertSee('Avg. order')
        ->assertSee('3 products need restocking')
        ->assertSee('Sales performance')
        ->assertSee('User activity')
        ->assertSee('Top desserts')
        ->assertSee('To do list')
        ->assertSee('Editable admin tasks')
        ->assertSee('Recent orders')
        ->assertSee('Recent activity')
        ->assertSee('₱48,290')
        ->assertSee('href="'.route('admin.dashboard').'" aria-current="page"', false)
        ->assertDontSee('href="'.route('admin.orders').'" aria-current="page"', false)
        ->assertSee('data-admin-todo', false)
        ->assertSee('data-sales-performance', false)
        ->assertDontSee('Track payments, fulfillment, and customer updates');
});
