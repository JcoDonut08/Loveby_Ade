<?php

test('forgot password page renders email recovery form', function () {
    $this->get(route('password.request'))
        ->assertSuccessful()
        ->assertSee('Forgot your password?')
        ->assertSee('Email')
        ->assertSee('Send OTP')
        ->assertSee('Back to login')
        ->assertSee('action="'.route('password.email').'"', false);
});

test('otp page renders six digit verification inputs', function () {
    $this->withSession(['auth.password_reset.email' => 'jane@example.com'])
        ->get(route('password.otp'))
        ->assertSuccessful()
        ->assertSee('Enter your 6-digit OTP.')
        ->assertSee('Verification code')
        ->assertSee('Verify OTP')
        ->assertSee('Resend code')
        ->assertSee('data-otp-inputs', false)
        ->assertSee('id="otp-1"', false)
        ->assertSee('id="otp-6"', false);
});

test('login renders a real submit form', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertSee('action="'.route('login.store').'"', false)
        ->assertSee('href="'.route('auth.google.redirect').'"', false)
        ->assertSee('Login with Google')
        ->assertSee('name="remember"', false)
        ->assertSee('value="1"', false)
        ->assertSee('type="submit"', false);
});

test('register renders google signup link', function () {
    $this->get(route('register'))
        ->assertSuccessful()
        ->assertSee('href="'.route('auth.google.redirect').'"', false)
        ->assertSee('Sign up with Google');
});

test('registration otp page renders six digit verification inputs', function () {
    $this->withSession(['auth.registration' => [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'password' => 'hashed-password',
    ]])
        ->get(route('register.otp'))
        ->assertSuccessful()
        ->assertSee('Verify your account.')
        ->assertSee('Account code')
        ->assertSee('Verify & Create Account', false)
        ->assertSee('data-otp-inputs', false)
        ->assertSee('id="register-otp-1"', false)
        ->assertSee('id="register-otp-6"', false);
});
