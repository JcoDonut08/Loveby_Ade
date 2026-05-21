<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            $this->notifications->readIdsFor($request->user()),
        );

        return view('pages.admin.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('unread', true)->count(),
            'totalCount' => $notifications->count(),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User) {
            $this->notifications->markAllReadFor($user);
        }

        return redirect()
            ->route('admin.notifications')
            ->with('status', 'Notifications marked as read.');
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User && in_array($notification, $this->notifications->notificationIds(), true)) {
            $this->notifications->markReadFor($user, $notification);
        }

        return redirect()->route('admin.notifications');
    }
}
