<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private AdminNotificationService $notifications) {}

    public function index(Request $request): View
    {
        $notifications = $this->notifications->notifications(
            $request->session()->get('read_admin_notifications', []),
        );

        return view('pages.admin.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('unread', true)->count(),
            'totalCount' => $notifications->count(),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->session()->put(
            'read_admin_notifications',
            array_values(array_unique([
                ...$request->session()->get('read_admin_notifications', []),
                ...$this->notifications->notificationIds(),
            ])),
        );

        return redirect()
            ->route('admin.notifications')
            ->with('status', 'Notifications marked as read.');
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        if (in_array($notification, $this->notifications->notificationIds(), true)) {
            $request->session()->put(
                'read_admin_notifications',
                array_values(array_unique([
                    ...$request->session()->get('read_admin_notifications', []),
                    $notification,
                ])),
            );
        }

        return redirect()->route('admin.notifications');
    }
}
