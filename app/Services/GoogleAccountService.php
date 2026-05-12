<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleAccountService
{
    public function findOrCreateUser(GoogleUser $googleUser): User
    {
        $googleId = (string) $googleUser->getId();
        $email = $this->normalizeEmail($googleUser->getEmail());

        if ($email === null) {
            throw ValidationException::withMessages([
                'email' => 'Google did not provide an email address for this account.',
            ]);
        }

        $user = User::query()
            ->where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user instanceof User) {
            $user->forceFill([
                'google_id' => $googleId,
                'google_avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();

            return $user;
        }

        return User::create([
            'name' => $googleUser->getName() ?: Str::before($email, '@'),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Str::random(48),
            'google_id' => $googleId,
            'google_avatar_url' => $googleUser->getAvatar(),
        ]);
    }

    private function normalizeEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }

        $email = mb_strtolower(trim($email));

        return $email === '' ? null : $email;
    }
}
