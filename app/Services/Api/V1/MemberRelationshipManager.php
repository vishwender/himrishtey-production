<?php

namespace App\Services\Api\V1;

use App\Models\Admin;
use App\Models\Member;

class MemberRelationshipManager
{
    public function details(Member $member): ?array
    {
        $identifier = trim((string) $member->relationship_manager);
        if ($identifier === '') {
            return null;
        }

        // Assignments store a staff name; older records can use a staff profile ID.
        // Never choose an arbitrary contact when names are ambiguous.
        $matches = Admin::query()->where('status', true)
            ->where(function ($query) use ($identifier) {
                $query->where('name', $identifier)->orWhere('profile_id', $identifier);
            })
            ->whereHas('sites', function ($query) use ($member) {
                $query->where('sites.status', true)
                    ->where('sites.database_name', $member->getConnection()->getDatabaseName());
            })->limit(2)->get();

        if ($matches->count() !== 1) {
            return null;
        }

        $manager = $matches->first();

        return [
            'name' => $manager->name,
            'contact_number' => trim((string) $manager->contact_number) ?: null,
        ];
    }
}
