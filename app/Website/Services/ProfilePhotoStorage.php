<?php

namespace App\Website\Services;

use App\Website\Models\Member;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoStorage
{
    public function save(Member $member, UploadedFile $photo): string
    {
        $path = $photo->store('', 'profile_photos');
        try {
            $member->forceFill(['photo' => $path, 'photo_approved' => 'No'])->saveOrFail();
        } catch (\Throwable $exception) {
            Storage::disk('profile_photos')->delete($path);
            throw $exception;
        }

        // Retain legacy files: other records or sites may still reference them.
        return $path;
    }
}
