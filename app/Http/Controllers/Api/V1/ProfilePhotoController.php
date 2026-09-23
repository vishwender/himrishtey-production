<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfilePhotoController extends Controller
{
    /**
     * Upload profile photo.
     */
    public function uploadProfilePhoto(Request $request): JsonResponse
    {
        /** @var Member $member */
        $member = $request->user();

        $request->validate([
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $file = $request->file('photo');

        $path = $file->storeAs(
            '',
            \App\Services\MemberPhotoFilename::make($member->id, $file->extension()),
            'profile_photos'
        );

        // Retain previous photos: gallery records may still reference them.

        /*
     * Profile photo is stored directly
     * in the members table.
     */
        $member->photo = $path;

        $member->profile_completed = $member->getProfileCompletion();

        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo uploaded successfully.',
            'data' => [
                'photo' => $member->photo,
                'url' => \Storage::disk('profile_photos')->url(
                    $member->photo
                ),
                'profile_completed' => $member->profile_completed,
            ],
        ]);
    }

    /**
     * Upload gallery photo.
     */
    public function uploadGalleryPhoto(Request $request): JsonResponse
    {
        /** @var Member $member */
        $member = $request->user();

        $request->validate([
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $file = $request->file('photo');

        $path = $file->storeAs(
            '',
            \App\Services\MemberPhotoFilename::make($member->id, $file->extension()),
            'profile_photos'
        );

        $photo = MemberPhoto::create([
            'member_id' => $member->id,
            'photo' => $path,
            'photo_approved' => 'No',
            'photo_privacy' => '1',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gallery photo uploaded successfully.',
            'data' => [
                'photo' => [
                    'id' => $photo->id,
                    'photo' => $photo->photo,
                    'url' => \Storage::disk('profile_photos')->url(
                        $photo->photo
                    ),
                    'photo_approved' => $photo->photo_approved,
                    'photo_privacy' => $photo->photo_privacy,
                ],
            ],
        ]);
    }
}
