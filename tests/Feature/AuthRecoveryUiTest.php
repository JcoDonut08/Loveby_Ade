<?php

test('forgot password page renders email recovery form', function () {
    $this->get(route('password.request'))
        ->assertSuccessful()
        ->assertSee('Forgot your password?')
        ->assertSee('Email')
        ->assertSee('Send OTP')
        ->assertSee('Back to login')
        ->assertSee('href="'.route('password.otp').'"', false);
});

test('otp page renders six digit verification inputs', function () {
    $this->get(route('password.otp'))
        ->assertSuccessful()
        ->assertSee('Enter your 6-digit OTP.')
        ->assertSee('Verification code')
        ->assertSee('Verify OTP')
        ->assertSee('Resend code')
        ->assertSee('data-otp-inputs', false)
        ->assertSee('id="otp-1"', false)
        ->assertSee('id="otp-6"', false);
});

test('login flows to a sign in otp page', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertSee('href="'.route('login.otp').'"', false);

    $this->get(route('login.otp'))
        ->assertSuccessful()
        ->assertSee('Verify your sign in.')
        ->assertSee('Sign-in code')
        ->assertSee('Verify & Sign In', false)
        ->assertSee('Back to login')
        ->assertSee('data-otp-inputs', false)
        ->assertSee('id="signin-otp-1"', false)
        ->assertSee('id="signin-otp-6"', false);
});
