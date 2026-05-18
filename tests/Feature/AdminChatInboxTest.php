<?php

test('admin chat inbox is removed from the admin workspace', function () {
    $this->actingAs(adminUser())
        ->get('/admin/chat-inbox')
        ->assertNotFound();

    $this->actingAs(adminUser())
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertDontSee('Chat Inbox')
        ->assertDontSee('/admin/chat-inbox');
});
