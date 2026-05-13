<?php

use Illuminate\Support\Facades\Route;

test('404 page uses the custom not found screen with an image', function () {
    config(['app.debug' => false]);

    $this->get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertSee('404')
        ->assertSee('Page not found')
        ->assertSee('error-icons/404.png', false)
        ->assertDontSee('Browse home')
        ->assertSee('<img', false)
        ->assertSee('data-error-illustration', false);
});

test('403 page uses the custom access denied screen with an image', function () {
    config(['app.debug' => false]);

    Route::get('/_test/error-403', fn () => abort(403));

    $this->get('/_test/error-403')
        ->assertForbidden()
        ->assertSee('403')
        ->assertSee('Access denied')
        ->assertSee('error-icons/403.png', false)
        ->assertSee('<img', false)
        ->assertSee('data-error-illustration', false);
});

test('500 page uses the custom server error screen with an image', function () {
    config(['app.debug' => false]);

    Route::get('/_test/error-500', fn () => abort(500));

    $this->get('/_test/error-500')
        ->assertStatus(500)
        ->assertSee('500')
        ->assertSee('Server error')
        ->assertSee('error-icons/500.png', false)
        ->assertSee('<img', false)
        ->assertSee('data-error-illustration', false);
});

test('503 page uses the custom service unavailable screen with an image', function () {
    config(['app.debug' => false]);

    Route::get('/_test/error-503', fn () => abort(503));

    $this->get('/_test/error-503')
        ->assertStatus(503)
        ->assertSee('503')
        ->assertSee('Service unavailable')
        ->assertSee('error-icons/503.png', false)
        ->assertSee('<img', false)
        ->assertSee('data-error-illustration', false);
});
