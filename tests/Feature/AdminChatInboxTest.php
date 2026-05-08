<?php

test('admin chat inbox renders the AI active conversation workspace', function () {
    $this->get(route('admin.chat-inbox'))
        ->assertSuccessful()
        ->assertSee('Chat Inbox')
        ->assertSee('AI-assisted customer conversations for orders, delivery, and custom desserts.')
        ->assertSee('Sophia Laurent')
        ->assertSee('Marcus Chen')
        ->assertSee('Amelia Brooks')
        ->assertSee('Liam O', false)
        ->assertSee('AI ACTIVE')
        ->assertSee('Take over from AI')
        ->assertSee('order #LBA-3421')
        ->assertSee('data-admin-chat-inbox', false)
        ->assertSee('href="'.route('admin.chat-inbox').'" aria-current="page"', false)
        ->assertDontSee('Suggested reply')
        ->assertDontSee('WAITING FOR ADMIN')
        ->assertDontSee('Waiting for admin')
        ->assertDontSee('RESOLVED')
        ->assertDontSee('Resolved');
});
