<?php

namespace App\Website\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    protected function diet(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        $normalize = static fn ($value) => is_string($value) && strcasecmp(trim($value), 'Ved') === 0 ? 'Veg' : $value;

        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: $normalize,
            set: $normalize,
        );
    }

    protected $connection = 'site';

    use HasFactory, Notifiable;

    protected $table = 'members';

    protected $guarded = [];

    protected $hidden = ['password', 'google_token', 'photo_password', 'remember_token'];

    public $timestamps = false;

    protected $dates = [
        'birth_date_time',
    ];

    // Automatically include these attributes
    protected $appends = [
        'age',
        'profile_completion',
        'wallet_balance',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function photos()
    {
        return $this->hasMany(MemberPhotos::class, 'member_id');
    }

    public function wallet()
    {
        return $this->hasOne(MemberWallet::class, 'member_id')
            ->latestOfMany(); // Latest wallet record
    }

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getAgeAttribute()
    {
        if (empty($this->birth_date_time)) {
            return null;
        }

        return Carbon::parse($this->birth_date_time)->age;
    }

    public function getProfileCompletionAttribute()
    {
        return $this->profileCompletionPercentage();
    }

    public function profileCompletionSections(): array
    {
        return app(\App\Services\MemberProfileCompletion::class)->sections($this);
    }

    public function profileCompletionPercentage(): int
    {
        return app(\App\Services\MemberProfileCompletion::class)->percentage($this);
    }

    public function getWalletBalanceAttribute()
    {
        return optional($this->wallet)->wallet_balance ?? 0;
    }
}
