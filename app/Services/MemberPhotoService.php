<?php

namespace App\Services;

use App\Jobs\ProcessMemberPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MemberPhotoService
{
    protected string $disk = 'public';

    /**
     * Store a member photo.
     */
    public function upload(
        int $memberId,
        UploadedFile $file,
        bool $setAsProfile = false
    ): object {

        $this->validateImage($file);

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $filename = MemberPhotoFilename::make($memberId, $extension);



        /*
    |--------------------------------------------------------------------------
    | Store original
    |--------------------------------------------------------------------------
    */

        $originalPath = $file->storeAs(
            '',
            $filename,
            'profile_photos'
        );

        /*
    |--------------------------------------------------------------------------
    | Create gallery record
    |--------------------------------------------------------------------------
    */

        $photoId = DB::connection('site')
            ->table('member_photos')
            ->insertGetId([
                'member_id' => $memberId,
                'photo' => $originalPath,
                'photo_approved' => 'No',
                'photo_privacy' => 1,
            ]);

        /*
    |--------------------------------------------------------------------------
    | Set as profile photo
    |--------------------------------------------------------------------------
    */

        if ($setAsProfile) {

            DB::connection('site')
                ->table('members')
                ->where('id', $memberId)
                ->update([
                    'photo' => $originalPath,
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Process image asynchronously
    |--------------------------------------------------------------------------
    */

        ProcessMemberPhoto::dispatch(
            $originalPath,
            'profile_photos'
        );

        /*
    |--------------------------------------------------------------------------
    | Return created photo
    |--------------------------------------------------------------------------
    */

        return DB::connection('site')
            ->table('member_photos')
            ->where('id', $photoId)
            ->first();
    }

    /**
     * Set an existing gallery photo as profile photo.
     */
    public function setAsProfile(
        int $memberId,
        int $photoId
    ): void {

        $photo = DB::connection('site')
            ->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (! $photo) {
            throw new RuntimeException('Photo not found.');
        }

        DB::connection('site')
            ->table('members')
            ->where('id', $memberId)
            ->update([
                'photo' => $photo->photo,
            ]);
    }

    /**
     * Approve a gallery photo.
     */
    public function approve(
        int $memberId,
        int $photoId
    ): void {

        $updated = DB::connection('site')
            ->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->update([
                'photo_approved' => 'Yes',
            ]);

        if (! $updated) {
            throw new RuntimeException('Photo not found.');
        }
    }

    /**
     * Reject / unapprove a gallery photo.
     */
    public function unapprove(
        int $memberId,
        int $photoId
    ): void {

        $updated = DB::connection('site')
            ->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->update([
                'photo_approved' => 'No',
            ]);

        if (! $updated) {
            throw new RuntimeException('Photo not found.');
        }
    }

    /**
     * Delete a gallery photo.
     */
    public function delete(
        int $memberId,
        int $photoId
    ): void {

        $db = DB::connection('site');

        $photo = $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (! $photo) {
            throw new RuntimeException('Photo not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Don't delete the physical file if it is currently
        | being used as the member's profile photo.
        |--------------------------------------------------------------------------
        */

        $member = $db->table('members')
            ->select('photo')
            ->where('id', $memberId)
            ->first();

        $isProfilePhoto =
            $member &&
            $member->photo === $photo->photo;

        /*
        |--------------------------------------------------------------------------
        | Delete database record
        |--------------------------------------------------------------------------
        */

        $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete physical file
        |--------------------------------------------------------------------------
        */

        if (! $isProfilePhoto) {
            $this->deletePhysicalPhoto($photo->photo);
        }
    }

    /**
     * Validate uploaded image.
     */
    protected function validateImage(
        UploadedFile $file
    ): void {

        if (! $file->isValid()) {
            throw new RuntimeException(
                'The uploaded image is invalid.'
            );
        }

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
        ];

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        if (! in_array($extension, $allowedExtensions, true)) {

            throw new RuntimeException(
                'Only JPG, JPEG, PNG and WebP images are allowed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10 MB maximum
        |--------------------------------------------------------------------------
        */

        if ($file->getSize() > 10 * 1024 * 1024) {

            throw new RuntimeException(
                'The image cannot be larger than 10 MB.'
            );
        }
    }

    /**
     * Get the public URL for a member photo.
     *
     * Supports:
     *
     * New uploads: public/photos/photo/filename.jpg
     *
     * Previous Laravel uploads:
     * members/123/original/filename.jpg
     *
     * LEGACY:
     * filename.jpg
     *
     * Legacy photos are stored separately for each site:
     *
     * public/photos/himrishtey/photo/
     * public/photos/gallpakki/photo/
     * public/photos/dogririshtey/photo/
     * public/photos/devbhoomi/photo/
     */
    public function url(?string $photo): ?string
    {
        if (empty($photo)) {
            return null;
        }

        $photo = ltrim($photo, '/');

        if (! str_contains($photo, '/') && Storage::disk('profile_photos')->exists($photo)) {
            return Storage::disk('profile_photos')->url($photo);
        }


        /*
    |--------------------------------------------------------------------------
    | New Laravel uploads
    |--------------------------------------------------------------------------
    |
    | Previous Laravel photos are stored under:
    | storage/app/public/members/{memberId}/...
    |
    */

        if (str_starts_with($photo, 'members/')) {
            return Storage::disk($this->disk)->url($photo);
        }

        /*
    |--------------------------------------------------------------------------
    | Legacy profile_photos
    |--------------------------------------------------------------------------
    */

        $legacyProfilePhotoPath = 'profile_photos/' . $photo;

        if (Storage::disk($this->disk)->exists($legacyProfilePhotoPath)) {
            return Storage::disk($this->disk)->url($legacyProfilePhotoPath);
        }

        /*
    |--------------------------------------------------------------------------
    | Site-specific legacy photos
    |--------------------------------------------------------------------------
    |
    | public/photos/himrishtey/photo/
    | public/photos/gallpakki/photo/
    | public/photos/dogririshtey/photo/
    | public/photos/devbhoomi/photo/
    |
    */

        $siteFolder = $this->legacySitePhotoFolder();

        if ($siteFolder) {
            $legacyPath = "photos/{$siteFolder}/photo/" . basename($photo);

            if (is_file(public_path($legacyPath))) {
                return asset($legacyPath);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Old shared photo directory fallback
    |--------------------------------------------------------------------------
    */

        $sharedLegacyPath = 'photos/photo/' . basename($photo);

        return asset($sharedLegacyPath);
    }

    /**
     * Get legacy photo directory for the currently connected site.
     */
    protected function legacySitePhotoFolder(): ?string
    {
        $database = DB::connection('site')
            ->getDatabaseName();

        return match ($database) {

            'newhm_base' => 'himrishtey',

            'newhm_gallpakki' => 'gallpakki',

            'newhm_dogririshtey' => 'dogririshtey',

            'newhm_devbhoomi' => 'devbhoomi',

            default => null,
        };
    }

    protected function deletePhysicalPhoto(?string $photo): void
    {
        if (empty($photo)) {
            return;
        }

        $photo = ltrim($photo, '/');

        if (! str_contains($photo, '/') && Storage::disk('profile_photos')->exists($photo)) {
            Storage::disk('profile_photos')->delete($photo);
            foreach (['large', 'medium', 'thumb'] as $variant) {
                Storage::disk('profile_photos')->delete($variant.'/'.pathinfo($photo, PATHINFO_FILENAME).'.webp');
            }
            return;
        }


        /*
    |--------------------------------------------------------------------------
    | New Laravel storage photo
    |--------------------------------------------------------------------------
    */

        if (str_starts_with($photo, 'members/')) {

            Storage::disk($this->disk)
                ->delete($photo);

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Legacy multisite photo
    |--------------------------------------------------------------------------
    */

        $siteFolder = $this->legacySitePhotoFolder();

        if ($siteFolder) {

            $legacyPath = public_path(
                "photos/{$siteFolder}/photo/{$photo}"
            );

            if (is_file($legacyPath)) {
                @unlink($legacyPath);

                return;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Old shared photo fallback
    |--------------------------------------------------------------------------
    */

        $sharedPath = public_path(
            "photos/photo/{$photo}"
        );

        if (is_file($sharedPath)) {
            @unlink($sharedPath);

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Storage fallback
    |--------------------------------------------------------------------------
    */

        Storage::disk($this->disk)
            ->delete($photo);
    }
}
