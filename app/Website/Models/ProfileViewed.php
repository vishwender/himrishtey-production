<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileViewed extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'profile_viewed';

    public $timestamps = false;

    public static function viewersFor(int $memberId): \Illuminate\Database\Eloquent\Builder
    {
        return Member::query()
            ->where('members.id', '!=', $memberId)
            ->whereRaw('LOWER(TRIM(members.active)) = ?', ['yes'])
            ->where(function ($query) {
                $query->whereNull('members.profile_hide')
                    ->orWhereRaw('LOWER(TRIM(members.profile_hide)) != ?', ['yes']);
            })
            ->whereIn('members.id', static::query()
                ->select('member_id')->where('viewed_profile_id', $memberId));
    }

    public static function recentViewersFor(int $memberId): \Illuminate\Database\Eloquent\Builder
    {
        return static::viewersFor($memberId)
            ->orderByDesc(static::query()->selectRaw('MAX(id)')
                ->whereColumn('member_id', 'members.id')
                ->where('viewed_profile_id', $memberId));
    }
}
