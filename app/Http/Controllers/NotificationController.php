<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CustomerNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private CustomerNotificationService $notifications) {}

    public function index(Request $request): View
    {
        return view('pages.notifications', [
            'notifications' => $this->notifications->notificationsFor(
                $request->user(),
                $this->notifications->readIdsFor($request->user()),
            ),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User) {
            $this->notifications->markAllReadFor($user);
        }

        return redirect()
            ->route('notifications')
            ->with('status', 'Notifications marked as read.');
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User && in_array($notification, $this->notifications->notificationIdsFor($user), true)) {
            $this->notifications->markReadFor($user, $notification);
        }

        return redirect()->route('notifications');
    }
}
