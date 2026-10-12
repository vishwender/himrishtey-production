<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberRotation extends Model
{
    protected $connection = 'site';

    protected $table = 'member_rotations';

    protected $fillable = [
        'member_id',
        'user_id',
        'admin_id',
        'days',
        'time',
        'next_rotation_at',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'next_rotation_at' => 'datetime',
        'completed_at' => 'datetime',
        'time' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (self $rotation) {
            app(\App\Services\MemberLogs::class)->record(
                (int) $rotation->member_id, 'Rotation Added', "Rotation #{$rotation->id} added",
                $rotation->created_at?->format('Y-m-d H:i:s')
            );
        });
    }

    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }
}
