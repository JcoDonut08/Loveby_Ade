<?php

use App\Http\Controllers\AccountController;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

test('a user can update editable account profile details without changing email', function () {
    $user = User::factory()->create([
        'name' => 'Mika Baker',
        'email' => 'mika@example.com',
    ]);

    $this->actingAs($user)
        ->patch(route('account.update'), [
            'name' => 'Mika Santos',
            'email' => 'changed@example.com',
            'contact_number' => '+63 917 555 1212',
            'address' => '12 Sweet Street, Manila',
        ])
        ->assertRedirect(route('account'))
        ->assertSessionHasNoErrors();

    expect($user->refresh())
        ->name->toBe('Mika Santos')
        ->email->toBe('mika@example.com')
        ->contact_number->toBe('+63 917 555 1212')
        ->address->toBe('12 Sweet Street, Manila');
});

test('a user can upload a profile photo', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $photo = uploadedPng('profile.png');

    $this->actingAs($user)
        ->patch(route('account.update'), [
            'name' => $user->name,
            'contact_number' => '',
            'address' => '',
            'profile_photo' => $photo,
        ])
        ->assertRedirect(route('account'))
        ->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->profile_photo_path);
});

test('the account view includes profile upload and editable profile fields', function () {
    $view = file_get_contents(resource_path('views/pages/account.blade.php'));

    expect($view)
        ->toContain("route('account.update')")
        ->toContain('enctype="multipart/form-data"')
        ->toContain('name="profile_photo"')
        ->toContain('data-profile-photo-input')
        ->toContain('data-profile-photo-preview-image')
        ->toContain('data-profile-photo-preview-fallback')
        ->toContain('name="contact_number"')
        ->toContain('name="address"')
        ->toContain('25 MB')
        ->toContain('Save changes')
        ->toContain('disabled');
});

test('the account controller uses the current app URL for stored profile photos', function () {
    $user = User::factory()->create([
        'profile_photo_path' => 'profile-photos/jco.png',
    ]);

    $this->actingAs($user);
    $view = app(AccountController::class)->index();

    expect($view->getData()['profilePhotoUrl'])->toBe(asset('storage/profile-photos/jco.png'));
});

test('the storefront header renders saved profile photos when one exists', function () {
    $view = file_get_contents(resource_path('views/components/home/store-header.blade.php'));

    expect($view)
        ->toContain('$profilePhotoUrl')
        ->toContain("asset('storage/'.")
        ->toContain('data-profile-photo-preview-image')
        ->toContain('data-profile-photo-preview-fallback');
});

function uploadedPng(string $name): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'profile-photo');

    file_put_contents(
        $path,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );

    return new UploadedFile($path, $name, 'image/png', null, true);
}
