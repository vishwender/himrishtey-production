<?php

namespace App\Website\Services;

use App\Website\Models\Member;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomepageProfiles
{
    public function get(): Collection
    {
        $member = Auth::guard('member')->user();
        $oppositeGender = match (strtolower(trim((string) $member?->gender))) {
            'male' => 'Female',
            'female' => 'Male',
            default => null,
        };

        if ($member && $oppositeGender === null) {
            return collect();
        }

        $genders = $member ? [$oppositeGender] : ['Male', 'Female'];
        $groups = [];
        foreach ($genders as $gender) {
            $groups[$gender] = Member::query()
                ->where('gender', $gender)
                ->when($member, fn ($query) => $query->where('id', '!=', $member->getAuthIdentifier()))
                ->where('active', 'Yes')
                ->where('member_type', 'Verified')
                ->where(fn ($query) => $query->whereNull('profile_hide')->orWhereRaw('LOWER(profile_hide) != ?', ['yes']))
                ->where('photo_approved', 'Yes')
                ->whereNotNull('photo')
                ->whereRaw("TRIM(photo) != ''")
                ->latest('id')
                ->limit($member ? 15 : ($gender === 'Male' ? 8 : 7))
                ->get();
        }

        $profiles = collect();
        for ($index = 0; $index < ($member ? 15 : 8); $index++) {
            foreach ($genders as $gender) {
                if (isset($groups[$gender][$index])) {
                    $profiles->push($groups[$gender][$index]);
                }
            }
        }

        return $profiles;
    }
}
