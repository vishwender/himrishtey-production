<?php

namespace App\Models;

use App\Support\HeightFormatter;
use Illuminate\Database\Eloquent\Model;

class SiteMember extends Model
{
    protected $table = 'members';

    protected $connection = 'site';

    public $timestamps = false;

    protected $guarded = [];

    public function scopeFilterStatus($query, ?string $status): void
    {
        if ($status === 'active') {
            $query->whereRaw('LOWER(TRIM(active)) = ?', ['yes']);
        } elseif ($status === 'inactive') {
            $query->where(function ($query) {
                $query->whereNull('active')
                    ->orWhereRaw('LOWER(TRIM(active)) IN (?, ?)', ['no', '']);
            });
        }
    }

    public function scopeFilterVisibility($query, ?string $visibility): void
    {
        if ($visibility === 'hidden') {
            $query->whereRaw('LOWER(TRIM(profile_hide)) = ?', ['yes']);
        } elseif ($visibility === 'visible') {
            $query->where(function ($query) {
                $query->whereNull('profile_hide')
                    ->orWhereRaw('LOWER(TRIM(profile_hide)) != ?', ['yes']);
            });
        }
    }


    /**
     * Format height.
     *
     * Examples:
     *
     * 5.7  => 5 ft 7 in
     * 5.10 => 5 ft 10 in
     * 5.11 => 5 ft 11 in
     * 6    => 6 ft 0 in
     */
    public function formatHeight($height): string
    {
        return HeightFormatter::format($height);
    }

    /**
     * Main profile photo.
     *
     * This comes from members.photo.
     */
    public function getProfilePhotoAttribute()
    {
        return $this->photo;
    }

    /**
     * Gallery photos.
     *
     * These come from member_photos.
     */
    public function photos()
    {
        return $this->hasMany(
            MemberPhoto::class,
            'member_id',
            'id'
        );
    }
}
