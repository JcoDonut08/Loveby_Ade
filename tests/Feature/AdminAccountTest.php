<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin account page renders editable profile controls', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.account'))
        ->assertSuccessful()
        ->assertSee('Account')
        ->assertSee('Manage your admin profile and access.')
        ->assertSee('Change profile photo')
        ->assertSee('Profile details')
        ->assertSee('Name')
        ->assertSee('Role')
        ->assertSee('Email')
        ->assertSee('Phone')
        ->assertSee('Profile note')
        ->assertSee('Save changes')
        ->assertSee('Logout')
        ->assertSee('action="'.route('admin.account.update').'"', false)
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('name="profile_photo"', false)
        ->assertSee('data-profile-photo-input', false)
        ->assertSee('name="contact_number_digits"', false)
        ->assertSee('+63-', false)
        ->assertSee('placeholder="0000000000"', false)
        ->assertSee($admin->name)
        ->assertSee('data-admin-account', false)
        ->assertSee('href="'.route('admin.account').'" aria-current="page"', false);
});

test('an admin can update account details from the admin account tab', function () {
    $admin = User::factory()->admin()->create([
        'name' => 'Ade Sweet',
        'email' => 'ade@example.com',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.account.update'), [
            'name' => 'Ade Santos',
            'email' => 'owner@example.com',
            'contact_number_digits' => '9123456789',
            'address' => 'Sweet Admin managing reports.',
        ])
        ->assertRedirect(route('admin.account'))
        ->assertSessionHasNoErrors();

    expect($admin->refresh())
        ->name->toBe('Ade Santos')
        ->email->toBe('owner@example.com')
        ->contact_number->toBe('+63-9123456789')
        ->address->toBe('Sweet Admin managing reports.');
});

test('admin account phone number requires the fixed philippine format', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'ade@example.com',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.account.update'), [
            'name' => 'Ade Santos',
            'email' => 'owner@example.com',
            'contact_number_digits' => '912345',
            'address' => 'Sweet Admin managing reports.',
        ])
        ->assertSessionHasErrors('contact_number_digits');
});

test('an admin can upload a profile photo from the admin account tab', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create([
        'name' => 'Ade Sweet',
        'email' => 'ade@example.com',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.account.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'contact_number_digits' => '',
            'address' => '',
            'profile_photo' => adminUploadedPng('admin-profile.png'),
        ])
        ->assertRedirect(route('admin.account'))
        ->assertSessionHasNoErrors();

    $admin->refresh();

    expect($admin->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($admin->profile_photo_path);
});

function adminUploadedPng(string $name): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'admin-profile-photo');

    file_put_contents(
        $path,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );

    return new UploadedFile($path, $name, 'image/png', null, true);
}
