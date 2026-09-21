<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SuccessStoryPhoto
{
    public static function url(?string $photo): string
    {
        if (! $photo) {
            return asset('uploads/success-stories/default-story.png');
        }
        $photo = basename($photo);
        if (Storage::disk('public')->exists('success-stories/'.$photo)) {
            return asset('storage/success-stories/'.$photo);
        }

        return asset('uploads/success-stories/'.$photo);
    }

    public static function delete(?string $photo): void
    {
        if (! $photo) {
            return;
        }
        $photo = basename($photo);
        Storage::disk('public')->delete('success-stories/'.$photo);
        $legacy = public_path('uploads/success-stories/'.$photo);
        if (is_file($legacy)) {
            unlink($legacy);
        }
    }
}
