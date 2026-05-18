<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LogoutController extends Controller
{
    public function __construct(private UserAuditLogger $auditLogger) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user !== null && Schema::hasColumn('users', 'last_active_at')) {
            $user->timestamps = false;
            $user->forceFill([
                'last_active_at' => now(),
            ])->saveQuietly();
            $user->timestamps = true;
        }

        $this->auditLogger->record(
            $user,
            'Logout',
            'Authentication',
            'User logged out.',
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
