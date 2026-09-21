<?php

namespace App\Website\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
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

    /**
     * Single source of truth for completion on the dashboard and editor.
     */
    public function profileCompletionSections(): array
    {
        return [
            'basic-info' => [
                'title' => 'Basic Info',
                'completed' => filled($this->about_me)
                    && filled($this->profile_created_for)
                    && filled($this->birth_date_time)
                    && filled($this->height)
                    && filled($this->religion)
                    && filled($this->cast)
                    && filled($this->marital_status)
                    && filled($this->country_living_in)
                    && filled($this->state_living_in)
                    && filled($this->city_living_in),
            ],
            'astro' => [
                'title' => 'Astro & Kundali',
                'completed' => filled($this->manglik) && filled($this->birth_place),
            ],
            'education' => [
                'title' => 'Education & Career',
                'completed' => filled($this->about_my_education)
                    && filled($this->education)
                    && filled($this->any_other_qualifications)
                    && filled($this->employed_in)
                    && filled($this->organization_name)
                    && filled($this->job_location)
                    && filled($this->occupation)
                    && filled($this->annual_income),
            ],
            'family' => [
                'title' => 'Family',
                'completed' => filled($this->about_family)
                    && filled($this->family_status)
                    && filled($this->native_place)
                    && filled($this->father_name)
                    && filled($this->father_occupation)
                    && filled($this->mother_name)
                    && filled($this->mother_occupation)
                    && filled($this->no_of_brothers)
                    && filled($this->married_brothers)
                    && filled($this->no_of_sisters)
                    && filled($this->married_sisters),
            ],
            'lifestyle' => [
                'title' => 'Lifestyle',
                'completed' => filled($this->diet)
                    && filled($this->is_smoking)
                    && filled($this->is_drinking)
                    && filled($this->any_disability)
                    && ($this->any_disability !== 'Yes' || filled($this->disability_detail)),
            ],
            'religion' => [
                'title' => 'Religion & Community',
                'completed' => filled($this->gotra) && filled($this->sub_cast),
            ],
            'preferences' => [
                'title' => 'Partner Preference',
                'completed' => filled($this->looking_for)
                    && filled($this->partner_age_from)
                    && filled($this->partner_age_to)
                    && filled($this->partner_height_from)
                    && filled($this->partner_height_to)
                    && filled($this->partner_religion)
                    && filled($this->partner_cast)
                    && filled($this->partner_mothertongue)
                    && filled($this->partner_education)
                    && filled($this->partner_occupation)
                    && filled($this->partner_annual_income_from)
                    && filled($this->partner_annual_income_to)
                    && filled($this->is_partner_smoking)
                    && filled($this->is_partner_drinking)
                    && filled($this->partner_diet)
                    && filled($this->is_partner_manglik)
                    && filled($this->about_my_partner),
            ],
            'contact' => [
                'title' => 'Contact Info',
                'completed' => filled($this->mobile_number) && filled($this->email),
            ],
        ];
    }

    public function profileCompletionPercentage(): int
    {
        $sections = $this->profileCompletionSections();
        $completed = collect($sections)->where('completed', true)->count();

        return (int) round(($completed / count($sections)) * 100);
    }

    public function getWalletBalanceAttribute()
    {
        return optional($this->wallet)->wallet_balance ?? 0;
    }
}
