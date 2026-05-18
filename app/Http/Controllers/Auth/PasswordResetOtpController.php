<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetOtpController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private UserAuditLogger $auditLogger,
    ) {}

    public function showEmailForm(): View
    {
        return view('pages.auth.forgot_password');
    }

    public function sendOtp(ForgotPasswordOtpRequest $request): RedirectResponse
    {
        $email = mb_strtolower($request->validated()['email']);
        $userExists = User::query()->where('email', $email)->exists();

        if (! $userExists) {
            return back()->with('status', 'If that email belongs to an account, we sent a 6-digit OTP.');
        }

        $request->session()->put('auth.password_reset.email', $email);
        $request->session()->forget('auth.password_reset.verified_at');

        $this->otpService->send($email, OtpService::PURPOSE_PASSWORD_RESET);

        return redirect()
            ->route('password.otp')
            ->with('status', 'We sent a 6-digit OTP to your email.');
    }

    public function showOtp(): View|RedirectResponse
    {
        if (! session()->has('auth.password_reset.email')) {
            return redirect()->route('password.request');
        }

        return view('pages.auth.otp', [
            'email' => session('auth.password_reset.email'),
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        $email = $request->session()->get('auth.password_reset.email');

        if (! is_string($email)) {
            return redirect()->route('password.request');
        }

        $isVerified = $this->otpService->verifyAndConsume(
            $email,
            OtpService::PURPOSE_PASSWORD_RESET,
            $request->validated()['otp_code'],
        );

        if (! $isVerified) {
            throw ValidationException::withMessages([
                'otp_code' => 'The verification code is invalid or expired.',
            ]);
        }

        $request->session()->put('auth.password_reset.verified_at', now()->timestamp);

        return redirect()->route('password.reset');
    }

    public function showResetForm(): View|RedirectResponse
    {
        if (! session()->has('auth.password_reset.email') || ! session()->has('auth.password_reset.verified_at')) {
            return redirect()->route('password.request');
        }

        return view('pages.auth.reset_password');
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $email = $request->session()->get('auth.password_reset.email');

        if (! is_string($email)) {
            return redirect()->route('password.request');
        }

        $user = User::query()->where('email', $email)->firstOrFail();
        $validated = $request->validated();

        $user->update([
            'password' => $validated['password'],
        ]);

        $this->auditLogger->record(
            $user,
            'Password Changed',
            'Authentication',
            'User password was changed after OTP verification.',
        );

        $request->session()->forget('auth.password_reset');

        return redirect()
            ->route('login')
            ->with('status', 'Your password has been changed. You can now log in.');
    }

    public function resendOtp(): RedirectResponse
    {
        $email = session('auth.password_reset.email');

        if (! is_string($email)) {
            return redirect()->route('password.request');
        }

        $this->otpService->send($email, OtpService::PURPOSE_PASSWORD_RESET);

        return back()->with('status', 'We sent a new 6-digit OTP.');
    }
}
