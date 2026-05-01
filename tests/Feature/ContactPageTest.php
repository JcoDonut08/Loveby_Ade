<?php

test('contact page renders the admin contact form', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('Contact Admin')
        ->assertSee('Send a message')
        ->assertSee('Contact form')
        ->assertSee('Email admin')
        ->assertSee('data-contact-form', false);
});
