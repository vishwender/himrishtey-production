<?php

namespace App\Website\Services;

use App\Website\Models\Member;
use App\Website\Models\MembershipPlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PublicPageData
{
    public function homeSummary(): array
    {
        $key = config('site.current.key', 'default').':public-home-summary:v2';
        // Cache only the fields displayed publicly, never serialized member models.
        $data = Cache::remember($key, now()->addMinutes(5), fn (): array => [
            'maleProfile' => Member::where('gender', 'Male')->where('is_trusted', 'trusted')
                ->where('active', 'Yes')->latest('id')
                ->first(['full_name', 'birth_date_time', 'city_living_in'])?->getAttributes(),
            'femaleProfile' => Member::where('gender', 'Female')->where('member_type', 'Verified')
                ->where('active', 'Yes')->latest('id')
                ->first(['full_name', 'birth_date_time', 'city_living_in'])?->getAttributes(),
            'totalprofiles' => Member::count(),
        ]);

        foreach (['maleProfile', 'femaleProfile'] as $field) {
            $data[$field] = isset($data[$field]) ? (object) $data[$field] : null;
        }

        return $data;
    }

    public function membershipPlans(): Collection
    {
        $key = config('site.current.key', 'default').':public-membership-plans:v2';
        $rows = Cache::remember($key, now()->addMinutes(10), fn (): array => MembershipPlan::where('id', '>', 0)->get()->map->getAttributes()->all()
        );

        return collect($rows)->map(fn (array $row) => (new MembershipPlan)->newFromBuilder($row));
    }
}
