<?php

use App\Models\Product;
use App\Models\User;
use App\Models\UserAuditLog;
use Illuminate\Database\Eloquent\Factories\Sequence;

test('admin audit logs page renders important user activity without sensitive request data', function () {
    $user = User::factory()->create(['name' => 'Maria Santos']);

    UserAuditLog::factory()->for($user)->create([
        'user_name' => 'Maria Santos',
        'user_email' => 'maria@example.com',
        'activity' => 'Login',
        'module' => 'Authentication',
        'description' => 'User logged in successfully.',
        'status' => 'success',
        'ip_address' => '203.0.113.55',
        'user_agent' => 'Private Browser 1.0',
        'metadata' => ['internal_note' => 'hidden'],
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.audit-logs'))
        ->assertSuccessful()
        ->assertSee('Audit Logs')
        ->assertSee('Date &amp; Time', false)
        ->assertSee('User')
        ->assertSee('Activity')
        ->assertSee('Module')
        ->assertSee('Description')
        ->assertSee('Status')
        ->assertSee('onchange="this.form.submit()"', false)
        ->assertDontSee('Apply')
        ->assertSee('Maria Santos')
        ->assertSee('maria@example.com')
        ->assertSee('M')
        ->assertSee('Login')
        ->assertSee('Authentication')
        ->assertSee('User logged in successfully.')
        ->assertSee('Success')
        ->assertSee('href="'.route('admin.audit-logs').'" aria-current="page"', false)
        ->assertDontSee('203.0.113.55')
        ->assertDontSee('Private Browser 1.0')
        ->assertDontSee('internal_note');
});

test('audit logs page has paginated controls that keep filters', function () {
    UserAuditLog::factory()
        ->count(6)
        ->sequence(fn (Sequence $sequence): array => [
            'user_name' => 'Audit User '.$sequence->index,
            'user_email' => 'audit-'.$sequence->index.'@example.com',
            'activity' => 'Login',
            'module' => 'Authentication',
            'description' => 'User logged in successfully.',
            'status' => 'success',
            'created_at' => now()->subMinutes($sequence->index),
        ])
        ->create();

    $this->actingAs(adminUser())
        ->get(route('admin.audit-logs', [
            'module' => 'Authentication',
            'status' => 'success',
        ]))
        ->assertSuccessful()
        ->assertSee('Rows per page')
        ->assertSee('5 rows')
        ->assertSee('Showing 1-5 of 6 logs')
        ->assertSee('Previous')
        ->assertSee('Next')
        ->assertSee('page=2', false)
        ->assertSee('module=Authentication', false)
        ->assertSee('status=success', false);

    $this->actingAs(adminUser())
        ->get(route('admin.audit-logs', [
            'module' => 'Authentication',
            'status' => 'success',
            'page' => 2,
        ]))
        ->assertSuccessful()
        ->assertSee('Showing 6-6 of 6 logs')
        ->assertSee('Previous')
        ->assertSee('Next');
});

test('audit logs page can filter by module and status', function () {
    UserAuditLog::factory()->create([
        'activity' => 'Login Failed',
        'module' => 'Authentication',
        'description' => 'Invalid credentials were submitted.',
        'status' => 'failed',
    ]);
    UserAuditLog::factory()->create([
        'activity' => 'Product Created',
        'module' => 'Products',
        'description' => 'Product Ube Cake was added to the catalog.',
        'status' => 'success',
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.audit-logs', [
            'module' => 'Authentication',
            'status' => 'failed',
        ]))
        ->assertSuccessful()
        ->assertSee('Login Failed')
        ->assertSee('Invalid credentials were submitted.')
        ->assertDontSee('Product Created')
        ->assertDontSee('Ube Cake');
});

test('audit logs page shows the related user profile photo and email when available', function () {
    $user = User::factory()->create([
        'name' => 'Luna Cakes',
        'email' => 'luna@example.com',
        'google_avatar_url' => 'https://example.com/luna.png',
    ]);

    UserAuditLog::factory()->for($user)->create([
        'user_name' => 'Luna Cakes',
        'user_email' => 'luna@example.com',
        'activity' => 'Profile Updated',
        'module' => 'Account',
        'description' => 'User updated account fields: name.',
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.audit-logs'))
        ->assertSuccessful()
        ->assertSee('Luna Cakes')
        ->assertSee('luna@example.com')
        ->assertSee('src="https://example.com/luna.png"', false)
        ->assertSee('alt="Luna Cakes profile photo"', false);
});

test('login attempts are recorded as important audit events', function () {
    $user = User::factory()->create([
        'name' => 'Jane Baker',
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'jane@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertDatabaseHas('user_audit_logs', [
        'user_id' => null,
        'activity' => 'Login Failed',
        'module' => 'Authentication',
        'description' => 'Invalid credentials were submitted.',
        'status' => 'failed',
    ]);

    $this->post(route('login.store'), [
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertDatabaseHas('user_audit_logs', [
        'user_id' => $user->id,
        'user_name' => 'Jane Baker',
        'user_email' => 'jane@example.com',
        'activity' => 'Login',
        'module' => 'Authentication',
        'description' => 'User logged in successfully.',
        'status' => 'success',
    ]);
});

test('admin product changes are recorded in audit logs', function () {
    $admin = adminUser();
    $product = Product::factory()->create([
        'title' => 'Old Cake',
        'slug' => 'old-cake',
        'category' => 'Cakes',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.products.update', $product), [
            'title' => 'New Cake',
            'description' => 'Freshly updated cake description.',
            'category' => 'Pastries',
            'price' => 220,
            'stock' => 5,
        ])
        ->assertRedirect(route('admin.products'));

    $this->assertDatabaseHas('user_audit_logs', [
        'user_id' => $admin->id,
        'user_name' => $admin->name,
        'activity' => 'Product Updated',
        'module' => 'Products',
        'description' => 'Product New Cake was updated.',
        'status' => 'success',
    ]);
});
