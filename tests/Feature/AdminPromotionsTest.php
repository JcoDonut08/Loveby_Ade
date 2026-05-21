<?php

use App\Http\Requests\Admin\StorePromotionRequest;
use App\Mail\PromotionCodeMail;
use App\Models\Promotion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

test('admin promotions page renders the front-end promo workspace', function () {
    Promotion::factory()->percentage(20)->create(['code' => 'SWEET20']);
    Promotion::factory()->fixed(50)->create(['code' => 'DONUTLOVE']);
    Promotion::factory()->ad()->create();

    $this->actingAs(adminUser())
        ->get(route('admin.promotions'))
        ->assertSuccessful()
        ->assertSee('Promotions')
        ->assertSee('Create discounts to delight customers.')
        ->assertSee('Search orders, products, customers...')
        ->assertSee('New Promotion')
        ->assertSee('Discount code')
        ->assertSee('Image ad')
        ->assertSee('Promo code')
        ->assertSee('Discount type')
        ->assertSee('Discount value')
        ->assertSee('Start date')
        ->assertSee('Expiry date')
        ->assertSee('Active promotion')
        ->assertSee('Announcement board image')
        ->assertSee('Upload up to 10 MB')
        ->assertSee('Promo Codes')
        ->assertSee('SWEET20')
        ->assertSee('DONUTLOVE')
        ->assertSee('Image Ad')
        ->assertSee('No revenue')
        ->assertSee('data-admin-promotions', false)
        ->assertSee('data-promotion-global-search', false)
        ->assertSee('role="switch"', false)
        ->assertSee('aria-label="Edit SWEET20 schedule"', false)
        ->assertSeeInOrder(['aria-label="Deactivate SWEET20"', 'aria-label="Edit SWEET20 schedule"'], false)
        ->assertSee('aria-label="Email SWEET20"', false)
        ->assertSee('aria-label="Delete SWEET20"', false)
        ->assertSee('Customer email')
        ->assertSee('Send promo code')
        ->assertSee('Save schedule')
        ->assertSee('href="'.route('admin.promotions').'" aria-current="page"', false)
        ->assertDontSee('aria-label="Manage SWEET20"', false)
        ->assertDontSee('Active Promos')
        ->assertDontSee('Image Ads')
        ->assertDontSee('Scheduled Drafts')
        ->assertDontSee('Email Ready')
        ->assertDontSee('Send email draft');
});

test('promotion image ads allow uploads up to ten megabytes', function () {
    $rules = (new StorePromotionRequest)->rules();

    expect($rules['image'])->toContain('max:10240');
});

test('admin can create toggle and delete promo codes', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->post(route('admin.promotions.store'), [
            'code' => ' weekend10 ',
            'kind' => Promotion::KIND_DISCOUNT,
            'discount_type' => Promotion::DISCOUNT_PERCENTAGE,
            'discount_value' => 10,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addWeek()->toDateString(),
            'is_active' => '1',
            'announcement_title' => 'Weekend treats',
            'announcement_body' => 'Use this at checkout.',
            'announcement_cta' => 'Claim Offer',
        ])
        ->assertRedirect(route('admin.promotions'));

    $promotion = Promotion::query()->where('code', 'WEEKEND10')->firstOrFail();

    expect($promotion->is_active)->toBeTrue()
        ->and($promotion->discount_type)->toBe(Promotion::DISCOUNT_PERCENTAGE);

    $this->patch(route('admin.promotions.toggle', $promotion))
        ->assertRedirect(route('admin.promotions'));

    expect($promotion->fresh()->is_active)->toBeFalse();

    $this->delete(route('admin.promotions.destroy', $promotion))
        ->assertRedirect(route('admin.promotions'));

    $this->assertDatabaseMissing('promotions', [
        'id' => $promotion->id,
    ]);
});

test('admin can update a promotion schedule', function () {
    $promotion = Promotion::factory()->percentage(20)->create([
        'code' => 'SCHEDULE20',
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addWeek()->toDateString(),
        'is_active' => true,
    ]);

    $this->actingAs(adminUser())
        ->patch(route('admin.promotions.update', $promotion), [
            'starts_at' => now()->addDay()->toDateString(),
            'expires_at' => now()->addDays(10)->toDateString(),
        ])
        ->assertRedirect(route('admin.promotions'));

    $promotion->refresh();

    expect($promotion->starts_at?->toDateString())->toBe(now()->addDay()->toDateString())
        ->and($promotion->expires_at?->toDateString())->toBe(now()->addDays(10)->toDateString())
        ->and($promotion->is_active)->toBeFalse();
});

test('admin can send a promo code to a customer email', function () {
    Mail::fake();

    $promotion = Promotion::factory()->percentage(15)->create([
        'code' => 'EMAIL15',
    ]);

    $this->actingAs(adminUser())
        ->post(route('admin.promotions.email', $promotion), [
            'email' => 'customer@example.com',
        ])
        ->assertRedirect(route('admin.promotions'));

    Mail::assertQueued(PromotionCodeMail::class, function (PromotionCodeMail $mail) use ($promotion): bool {
        return $mail->promotion->is($promotion);
    });
});

test('admin can create an image ad promotion with only an upload', function () {
    Storage::fake('public');

    $this->actingAs(adminUser())
        ->post(route('admin.promotions.store'), [
            'kind' => Promotion::KIND_AD,
            'image' => UploadedFile::fake()->createWithContent(
                'announcement.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='),
            ),
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.promotions'));

    $promotion = Promotion::query()->where('kind', Promotion::KIND_AD)->firstOrFail();

    expect($promotion->code)->toStartWith('AD-')
        ->and((float) $promotion->discount_value)->toBe(0.0)
        ->and($promotion->image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($promotion->image_path);
});

test('active promotion appears on the storefront announcement board', function () {
    Promotion::factory()->percentage(15)->create([
        'code' => 'BAKE15',
        'announcement_title' => 'Save on weekend dessert boxes.',
        'announcement_body' => 'Use BAKE15 for a limited checkout discount.',
        'announcement_cta' => 'Shop the Promo',
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('BAKE15')
        ->assertSee('Save on weekend dessert boxes.')
        ->assertSee('Use BAKE15 for a limited checkout discount.')
        ->assertSee('Shop the Promo');
});

test('storefront announcement board renders discount and image ad slides', function () {
    Promotion::factory()->percentage(15)->create([
        'code' => 'BAKE15',
        'announcement_title' => 'Save on weekend dessert boxes.',
    ]);
    Promotion::factory()->ad()->create([
        'image_path' => 'promotions/sale-board.jpg',
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('aria-label="Announcement board"', false)
        ->assertSee('data-promo-carousel', false)
        ->assertSee('data-promo-track', false)
        ->assertSee('data-promo-next', false)
        ->assertSee('data-promo-dot', false)
        ->assertSee('BAKE15')
        ->assertSee('Save on weekend dessert boxes.')
        ->assertSee('promotions/sale-board.jpg');
});
