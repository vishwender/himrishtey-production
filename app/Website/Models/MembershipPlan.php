<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'membership_plans';

    public $timestamps = false;

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type', 'id');
    }

    public function plans()
    {
        return $this->belongsTo(Member::class, 'plan_id', 'id');
    }

    public function walletRewardPoints(): int
    {
        $normalizedName = strtoupper(str_replace(' ', '', trim((string) $this->plan_name)));

        return match ($normalizedName) {
            'SILVER' => 60,
            'GOLD' => 220,
            'GOLD+', 'PREMIUM+' => 600,
            default => 0,
        };
    }
}
