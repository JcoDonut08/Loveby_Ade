<?php

use App\Mail\ContactMessageMail;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(LazilyRefreshDatabase::class);

test('contact page renders the admin contact form', function () {
    config(['mail.from.address' => 'otp-sender@example.test']);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('Contact Admin')
        ->assertSee('Send a message')
        ->assertSee('Contact form')
        ->assertSee('Email admin')
        ->assertSee('otp-sender@example.test')
        ->assertSee('action="'.route('contact.store').'"', false);
});

test('contact page prefills authenticated user name and email', function () {
    $user = User::factory()->create([
        'name' => 'Jane Baker',
        'email' => 'jane@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('value="Jane Baker"', false)
        ->assertSee('value="jane@example.com"', false)
        ->assertSee('readonly', false);
});

test('a guest can send a contact message to the configured mail sender address', function () {
    Mail::fake();
    config(['mail.from.address' => 'otp-sender@example.test']);

    $this->post(route('contact.store'), [
        'name' => 'Mika Santos',
        'email' => 'mika@example.com',
        'concern' => 'Product question',
        'order_number' => 'LA-1001',
        'message' => 'Do you have custom cupcake boxes available?',
    ])
        ->assertRedirect(route('contact'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status');

    Mail::assertQueued(ContactMessageMail::class, function (ContactMessageMail $mail): bool {
        return $mail->hasTo('otp-sender@example.test')
            && $mail->contactMessage['name'] === 'Mika Santos'
            && $mail->contactMessage['email'] === 'mika@example.com'
            && $mail->contactMessage['concern'] === 'Product question'
            && $mail->contactMessage['order_number'] === 'LA-1001'
            && $mail->contactMessage['message'] === 'Do you have custom cupcake boxes available?';
    });
});

test('authenticated contact messages use the signed in user identity', function () {
    Mail::fake();
    config(['mail.from.address' => 'otp-sender@example.test']);

    $user = User::factory()->create([
        'name' => 'Luna Cakes',
        'email' => 'luna@example.com',
    ]);

    $this->actingAs($user)
        ->post(route('contact.store'), [
            'name' => 'Spoofed Name',
            'email' => 'spoofed@example.com',
            'concern' => 'Order follow-up',
            'message' => 'Please check the status of my dessert order.',
        ])
        ->assertRedirect(route('contact'))
        ->assertSessionHasNoErrors();

    Mail::assertQueued(ContactMessageMail::class, function (ContactMessageMail $mail): bool {
        return $mail->hasTo('otp-sender@example.test')
            && $mail->contactMessage['name'] === 'Luna Cakes'
            && $mail->contactMessage['email'] === 'luna@example.com';
    });
});

test('contact message email includes the submitted details', function () {
    $mail = new ContactMessageMail([
        'name' => 'Mika Santos',
        'email' => 'mika@example.com',
        'concern' => 'Payment or delivery help',
        'order_number' => null,
        'message' => 'The delivery address needs a small correction.',
    ]);

    $mail
        ->assertSeeInHtml('Mika Santos')
        ->assertSeeInHtml('mika@example.com')
        ->assertSeeInHtml('Payment or delivery help')
        ->assertSeeInHtml('Not provided')
        ->assertSeeInHtml('The delivery address needs a small correction.');
});
