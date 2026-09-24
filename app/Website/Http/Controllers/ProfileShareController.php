<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Member;
use App\Website\Services\ProfilePhotoUrl;
use Illuminate\Http\Request;

class ProfileShareController extends Controller
{
    public function show(Request $request, string $profileId)
    {
        $profile = Member::query()
            ->where('profile_id', $profileId)
            ->where('active', 'Yes')
            ->where(fn($query) => $query->whereNull('profile_hide')->orWhereRaw('LOWER(profile_hide) != ?', ['yes']))
            ->firstOrFail();

        $photo = ! empty($profile->photo)
            && ($profile->photo_approved === 'Yes' || trim((string) $profile->photo_approved) === '')
            ? ProfilePhotoUrl::get($profile->photo)
            : asset('images/profile_photos/' . ($profile->gender === 'Male' ? 'boy.jpg' : 'girl.jpg'));

        $description = collect([
            $profile->profile_id,
            $profile->religion,
            $profile->cast,
            $profile->city_living_in
        ])
            ->filter()->implode(' · ');

        return response()->view('dashboard.profile.share-preview', [
            'name' => $profile->full_name,
            'photo' => $photo,
            'description' => $description,
            'previewUrl' => $request->fullUrl(),
            'profileUrl' => route('view-profile', $profile->profile_id),
        ])->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
