<?php

use App\Mail\OtpCodeMail;
use App\Models\AuthOtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(LazilyRefreshDatabase::class);

test('a user can log in and see the profile menu on the homepage', function () {
    $user = User::factory()->create([
        'name' => 'Jane Baker',
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Jane Baker')
        ->assertSee('View orders')
        ->assertSee('Account')
        ->assertSee('View delivered products')
        ->assertSee('aria-haspopup="menu"', false);
});

test('remember me creates the recaller cookie on login', function () {
    User::factory()->create([
        'email' => 'remember@example.com',
        'password' => 'secret-password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'remember@example.com',
        'password' => 'secret-password',
        'remember' => '1',
    ])
        ->assertRedirect(route('home'))
        ->assertCookie(Auth::guard()->getRecallerName());
});

test('an admin is redirected to the dashboard after login', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);
});

test('google login redirects to google account selection', function () {
    Socialite::fake('google');

    $this->get(route('auth.google.redirect'))
        ->assertRedirect('https://socialite.fake/google/authorize');
});

test('google callback creates and authenticates a user', function () {
    Socialite::fake('google', googleUser([
        'id' => 'google-user-123',
        'name' => 'Google Baker',
        'email' => 'google-baker@example.com',
        'avatar' => 'https://example.com/avatar.png',
    ]));

    $this->get(route('auth.google.callback'))
        ->assertRedirect(route('home'));

    $createdUser = User::query()->where('email', 'google-baker@example.com')->firstOrFail();

    $this->assertAuthenticatedAs($createdUser);
    expect($createdUser)
        ->google_id->toBe('google-user-123')
        ->google_avatar_url->toBe('https://example.com/avatar.png')
        ->email_verified_at->not->toBeNull();
});

test('google callback links an existing email account', function () {
    $existingUser = User::factory()->create([
        'email' => 'linked@example.com',
        'google_id' => null,
        'email_verified_at' => null,
    ]);

    Socialite::fake('google', googleUser([
        'id' => 'linked-google-user',
        'name' => 'Linked Baker',
        'email' => 'linked@example.com',
        'avatar' => 'https://example.com/linked.png',
    ]));

    $this->get(route('auth.google.callback'))
        ->assertRedirect(route('home'));

    expect($existingUser->refresh())
        ->google_id->toBe('linked-google-user')
        ->google_avatar_url->toBe('https://example.com/linked.png')
        ->email_verified_at->not->toBeNull();

    $this->assertAuthenticatedAs($existingUser);
});

test('signup sends an otp and creates the user after verification', function () {
    Mail::fake();
    $password = 'SweetCake#2026';

    $this->post(route('register.store'), [
        'username' => 'Luna Cakes',
        'email' => 'luna@example.com',
        'password' => $password,
    ])->assertRedirect(route('register.otp'));

    Mail::assertQueued(OtpCodeMail::class);

    AuthOtpCode::query()->where('email', 'luna@example.com')->update([
        'code_hash' => Hash::make('123456'),
    ]);

    $this->post(route('register.otp.verify'), [
        'otp' => ['1', '2', '3', '4', '5', '6'],
    ])->assertRedirect(route('login'));

    $createdUser = User::query()->where('email', 'luna@example.com')->firstOrFail();

    $this->assertModelExists($createdUser);
    expect($createdUser->name)->toBe('Luna Cakes');

    $this->post(route('login.store'), [
        'email' => 'luna@example.com',
        'password' => $password,
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($createdUser);
});

test('signup rejects weak passwords', function () {
    Mail::fake();

    $this->from(route('register'))
        ->post(route('register.store'), [
            'username' => 'Weak Password User',
            'email' => 'weak-password@example.com',
            'password' => 'secret-password',
        ])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors('password');

    Mail::assertNothingQueued();
});

test('forgot password otp allows the user to change their password', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'reset@example.com',
        'password' => 'old-password',
    ]);

    $this->post(route('password.email'), [
        'email' => 'reset@example.com',
    ])->assertRedirect(route('password.otp'));

    Mail::assertQueued(OtpCodeMail::class);

    AuthOtpCode::query()->where('email', 'reset@example.com')->update([
        'code_hash' => Hash::make('654321'),
    ]);

    $this->post(route('password.otp.verify'), [
        'otp' => ['6', '5', '4', '3', '2', '1'],
    ])->assertRedirect(route('password.reset'));

    $this->put(route('password.update'), [
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ])->assertRedirect(route('login'));

    expect(Hash::check('new-secret-password', $user->refresh()->password))->toBeTrue();
});

function googleUser(array $attributes): SocialiteUser
{
    return (new SocialiteUser)
        ->setRaw($attributes)
        ->map([
            'id' => $attributes['id'],
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'avatar' => $attributes['avatar'],
        ]);
}
