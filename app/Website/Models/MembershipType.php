<?php

namespace App\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    protected $connection = 'site';

    use HasFactory;

    protected $table = 'membership_type';

    public $timestamps = false;

    public function membershipPlans()
    {
        return $this->belongsTo(MembershipPlan::class);
    }
}
