<?php

test('admin account page renders editable profile controls', function () {
    $this->get(route('admin.account'))
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
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('data-admin-account', false)
        ->assertSee('href="'.route('admin.account').'" aria-current="page"', false);
});
