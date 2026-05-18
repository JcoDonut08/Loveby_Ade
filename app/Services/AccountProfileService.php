<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AccountProfileService
{
    /**
     * @param  array{name: string, email?: string, contact_number?: string|null, address?: string|null}  $attributes
     * @return array<int, string>
     */
    public function update(User $user, array $attributes, ?UploadedFile $profilePhoto): array
    {
        unset($attributes['contact_number_digits'], $attributes['profile_photo']);
        $oldProfilePhotoPath = null;

        if ($profilePhoto !== null) {
            $oldProfilePhotoPath = $user->profile_photo_path;
            $attributes['profile_photo_path'] = $profilePhoto->store('profile-photos', 'public');
        }

        $user->fill($attributes);

        $changedFields = collect(array_keys($user->getDirty()))
            ->reject(fn (string $field): bool => $field === 'updated_at')
            ->map(fn (string $field): string => match ($field) {
                'contact_number' => 'contact number',
                'profile_photo_path' => 'profile photo',
                default => str_replace('_', ' ', $field),
            })
            ->values()
            ->all();

        $user->save();

        if ($oldProfilePhotoPath !== null) {
            Storage::disk('public')->delete($oldProfilePhotoPath);
        }

        return $changedFields;
    }
}
