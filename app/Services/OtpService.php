<?php

namespace App\Services;

use App\Mail\OtpCodeMail;
use App\Models\AuthOtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public const PURPOSE_REGISTRATION = 'registration';

    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    public function send(string $email, string $purpose): void
    {
        $code = (string) random_int(100000, 999999);

        AuthOtpCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->delete();

        AuthOtpCode::create([
            'email' => $email,
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new OtpCodeMail($code, $purpose));
    }

    public function verifyAndConsume(string $email, string $purpose, string $code): bool
    {
        $otpCode = AuthOtpCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($otpCode === null || $otpCode->attempts >= 5) {
            return false;
        }

        if (! Hash::check($code, $otpCode->code_hash)) {
            $otpCode->increment('attempts');

            return false;
        }

        $otpCode->forceFill([
            'consumed_at' => now(),
        ])->save();

        return true;
    }
}
