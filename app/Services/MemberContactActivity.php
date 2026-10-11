<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MemberContactActivity
{
    public function query(int $memberId): Builder
    {
        $db = DB::connection('site');
        $schema = $db->getSchemaBuilder();
        $current = $schema->hasTable('viewed_contacts');
        $legacy = $schema->hasTable('contact_viewed');
        $base = $db->query()->selectRaw('NULL as id, NULL as viewed_profile_id')->whereRaw('1 = 0');
        if ($current) {
            $base = $db->table('viewed_contacts')->where('member_id', $memberId)
                ->select(['id', 'profile_id as viewed_profile_id']);
        }
        if ($legacy) {
            $old = $db->table('contact_viewed as legacy')->where('legacy.member_id', $memberId)
                ->select(['legacy.id', 'legacy.viewed_profile_id']);
            if ($current) {
                $old->whereNotExists(fn ($query) => $query->selectRaw('1')->from('viewed_contacts as current')
                    ->whereColumn('current.member_id', 'legacy.member_id')
                    ->whereColumn('current.profile_id', 'legacy.viewed_profile_id'));
                $base->unionAll($old);
            } else {
                $base = $old;
            }
        }

        return $db->query()->fromSub($base, 'cv')->leftJoin('members as m', 'm.id', '=', 'cv.viewed_profile_id')
            ->select(['cv.id', 'cv.viewed_profile_id', 'm.profile_id as viewed_profile_id_display', 'm.full_name as viewed_full_name']);
    }
}
