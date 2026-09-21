<?php

namespace App\Website\Services;

use App\Website\Models\Member;
use Illuminate\Support\Collection;

class HomepageProfiles
{
    public function get(): Collection
    {
        $groups = [];
        foreach (['Male', 'Female'] as $gender) {
            $groups[$gender] = Member::query()
                ->where('gender', $gender)
                ->where('active', 'Yes')
                ->where('member_type', 'Verified')
                ->where(fn ($query) => $query->whereNull('profile_hide')->orWhereRaw('LOWER(profile_hide) != ?', ['yes']))
                ->where('photo_approved', 'Yes')
                ->whereNotNull('photo')
                ->whereRaw("TRIM(photo) != ''")
                ->latest('id')
                ->limit($gender === 'Male' ? 8 : 7)
                ->get();
        }

        $profiles = collect();
        for ($index = 0; $index < 8; $index++) {
            foreach (['Male', 'Female'] as $gender) {
                if (isset($groups[$gender][$index])) {
                    $profiles->push($groups[$gender][$index]);
                }
            }
        }

        return $profiles;
    }
}
