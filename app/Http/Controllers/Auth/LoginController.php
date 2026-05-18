<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\CartService;
use App\Services\FavoriteService;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private CartService $cart,
        private FavoriteService $favorites,
        private UserAuditLogger $auditLogger,
    ) {}

    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->homeRouteFor(Auth::user()));
        }

        return view('pages.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $this->auditLogger->record(
                null,
                'Login Failed',
                'Authentication',
                'Invalid credentials were submitted.',
                'failed',
            );

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $this->cart->mergeSessionIntoUser($request->session(), $request->user());
        $this->favorites->mergeSessionIntoUser($request->session(), $request->user());
        $this->auditLogger->record(
            $request->user(),
            'Login',
            'Authentication',
            'User logged in successfully.',
        );

        return redirect()->intended(route($this->homeRouteFor($request->user())));
    }

    private function homeRouteFor(?User $user): string
    {
        return $user?->isAdmin() === true ? 'admin.dashboard' : 'home';
    }
}
