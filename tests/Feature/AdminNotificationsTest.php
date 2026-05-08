<?php

test('admin notifications page renders paginated notification controls', function () {
    $this->get(route('admin.notifications'))
        ->assertSuccessful()
        ->assertSee('Notifications')
        ->assertSee('Everything happening across your shop.')
        ->assertSee('Notification center')
        ->assertSee('3 unread - 12 total')
        ->assertSee('New order received')
        ->assertSee('Low stock alert')
        ->assertSee('Rows per page')
        ->assertSee('6 rows')
        ->assertSee('9 rows')
        ->assertSee('12 rows')
        ->assertSee('Showing 1-6 of 12 notifications')
        ->assertSee('Previous')
        ->assertSee('Next')
        ->assertSee('data-admin-notifications', false)
        ->assertSee('data-notification-row', false)
        ->assertSee('data-notification-page-size', false)
        ->assertSee('data-notification-pagination-status', false)
        ->assertSee('data-notification-page-buttons', false)
        ->assertSee('href="'.route('admin.notifications').'" aria-current="page"', false);
});
