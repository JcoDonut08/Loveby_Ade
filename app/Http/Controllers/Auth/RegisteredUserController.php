<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private UserAuditLogger $auditLogger,
    ) {}

    public function show(): View
    {
        return view('pages.auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $email = mb_strtolower($validated['email']);

        $request->session()->put('auth.registration', [
            'name' => $validated['username'],
            'email' => $email,
            'password' => Hash::make($validated['password']),
        ]);

        $this->otpService->send($email, OtpService::PURPOSE_REGISTRATION);

        return redirect()
            ->route('register.otp')
            ->with('status', 'We sent a 6-digit OTP to your email.');
    }

    public function showOtp(): View|RedirectResponse
    {
        if (! session()->has('auth.registration.email')) {
            return redirect()->route('register');
        }

        return view('pages.auth.register_otp', [
            'email' => session('auth.registration.email'),
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        $registration = $request->session()->get('auth.registration');

        if (! is_array($registration) || ! isset($registration['email'], $registration['name'], $registration['password'])) {
            return redirect()->route('register');
        }

        $isVerified = $this->otpService->verifyAndConsume(
            $registration['email'],
            OtpService::PURPOSE_REGISTRATION,
            $request->validated()['otp_code'],
        );

        if (! $isVerified) {
            throw ValidationException::withMessages([
                'otp_code' => 'The verification code is invalid or expired.',
            ]);
        }

        $user = User::create([
            'name' => $registration['name'],
            'email' => $registration['email'],
            'password' => $registration['password'],
            'email_verified_at' => now(),
        ]);

        $this->auditLogger->record(
            $user,
            'Registered',
            'Authentication',
            'User account was verified and created.',
        );

        $request->session()->forget('auth.registration');

        return redirect()
            ->route('login')
            ->with('status', 'Your account is verified. You can now log in.');
    }

    public function resendOtp(): RedirectResponse
    {
        $email = session('auth.registration.email');

        if (! is_string($email)) {
            return redirect()->route('register');
        }

        $this->otpService->send($email, OtpService::PURPOSE_REGISTRATION);

        return back()->with('status', 'We sent a new 6-digit OTP.');
    }
}
