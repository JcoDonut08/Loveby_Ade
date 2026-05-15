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
                $request->session()->get('read_customer_notifications', []),
            ),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User) {
            $request->session()->put(
                'read_customer_notifications',
                array_values(array_unique([
                    ...$request->session()->get('read_customer_notifications', []),
                    ...$this->notifications->notificationIdsFor($user),
                ])),
            );
        }

        return redirect()
            ->route('notifications')
            ->with('status', 'Notifications marked as read.');
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User && in_array($notification, $this->notifications->notificationIdsFor($user), true)) {
            $request->session()->put(
                'read_customer_notifications',
                array_values(array_unique([
                    ...$request->session()->get('read_customer_notifications', []),
                    $notification,
                ])),
            );
        }

        return redirect()->route('notifications');
    }
}
