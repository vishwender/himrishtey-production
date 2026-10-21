<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentInterest extends Model
{
    protected $table = 'sent_interests';

    protected $connection = 'application';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'profile_id',
        'status',
        'created_at',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'profile_id' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Member who sent the interest.
     *
     * sent_interests.member_id -> members.id
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            Member::class,
            'member_id',
            'id'
        );
    }

    /**
     * Member who received the interest.
     *
     * sent_interests.profile_id -> members.id
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(
            Member::class,
            'profile_id',
            'id'
        );
    }
}
