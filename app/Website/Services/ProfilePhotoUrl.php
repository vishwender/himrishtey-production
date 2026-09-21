<?php

namespace App\Website\Services;

use Illuminate\Support\Facades\Storage;

class ProfilePhotoUrl
{
    public static function get(?string $photo): ?string
    {
        if (! $photo) {
            return null;
        }
        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            return $photo;
        }
        $photo = ltrim($photo, '/');
        if (str_starts_with($photo, 'members/')) {
            return asset('storage/'.$photo);
        }
        if (str_starts_with($photo, 'storage/') || str_starts_with($photo, 'photos/')) {
            return asset($photo);
        }
        foreach (['photos/photo/', 'uploads/gallery/'] as $directory) {
            if (is_file(public_path($directory.$photo))) {
                return asset($directory.$photo);
            }
        }
        foreach (['profile_photos/'.$photo, $photo] as $stored) {
            if (Storage::disk('public')->exists($stored)) {
                return asset('storage/'.$stored);
            }
        }
        $path = 'photos/photo/'.$photo;
        if (is_file(public_path($path))) {
            return asset($path);
        }

        // Keep legacy images available when the local database references production uploads.
        $origin = config('site.sites')[config('site.current.key')]['app_url'] ?? config('app.url');

        return rtrim($origin, '/').'/'.$path;
    }
}
