<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SuccessStoryPhoto
{
    public static function isHimrishtey(): bool
    {
        // Use the selected database so admin and API requests resolve the same site.
        return config('database.connections.site.database') === 'newhm_base';
    }

    public static function directory(string $source = 'admin'): string
    {
        return static::isHimrishtey()
            ? '/home/himrishteymain/public_html/uploads/success-stories'
            : public_path($source === 'api' ? 'photos/ss' : 'uploads/success-stories');
    }

    public static function store(UploadedFile $file, string $source = 'admin'): string
    {
        if ($source === 'member' && ! static::isHimrishtey()) {
            return basename($file->store('success-stories', 'public'));
        }

        $filename = 'ss-photo-'.Str::uuid().'.'.$file->extension();
        $file->move(static::directory($source), $filename);

        return $filename;
    }

    public static function url(?string $photo, string $source = 'website'): string
    {
        if (! $photo) {
            return asset('uploads/success-stories/default-story.png');
        }
        $photo = basename($photo);
        if (static::isHimrishtey() && is_file(static::directory().'/'.$photo)) {
            return 'https://himrishtey.com/uploads/success-stories/'.rawurlencode($photo);
        }
        if ($source === 'api') {
            return asset('photos/ss/'.$photo);
        }
        if (Storage::disk('public')->exists('success-stories/'.$photo)) {
            return asset('storage/success-stories/'.$photo);
        }

        return asset('uploads/success-stories/'.$photo);
    }

    public static function delete(?string $photo, string $source = 'website'): void
    {
        if (! $photo) {
            return;
        }
        $photo = basename($photo);
        if (static::isHimrishtey()) {
            $path = static::directory().'/'.$photo;
            if (is_file($path)) {
                unlink($path);
                return;
            }
        }
        if ($source === 'api') {
            $path = public_path('photos/ss/'.$photo);
            if (is_file($path)) {
                unlink($path);
            }
            return;
        }
        Storage::disk('public')->delete('success-stories/'.$photo);
        $legacy = public_path('uploads/success-stories/'.$photo);
        if (is_file($legacy)) {
            unlink($legacy);
        }
    }
}
