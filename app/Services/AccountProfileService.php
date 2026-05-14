<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AccountProfileService
{
    /**
     * @param  array{name: string, email?: string, contact_number?: string|null, address?: string|null}  $attributes
     */
    public function update(User $user, array $attributes, ?UploadedFile $profilePhoto): void
    {
        unset($attributes['contact_number_digits'], $attributes['profile_photo']);
        $oldProfilePhotoPath = null;

        if ($profilePhoto !== null) {
            $oldProfilePhotoPath = $user->profile_photo_path;
            $attributes['profile_photo_path'] = $profilePhoto->store('profile-photos', 'public');
        }

        $user->update($attributes);

        if ($oldProfilePhotoPath !== null) {
            Storage::disk('public')->delete($oldProfilePhotoPath);
        }
    }
}
