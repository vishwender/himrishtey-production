<?php

namespace App\Services;

class MemberProfileCompletion
{
    public function sections(object $member): array
    {
        return [
            'basic-info' => [
                'title' => 'Basic Info',
                'completed' => filled($member->about_me)
                    && filled($member->profile_created_for)
                    && filled($member->birth_date_time)
                    && filled($member->height)
                    && filled($member->religion)
                    && filled($member->cast)
                    && filled($member->marital_status)
                    && filled($member->country_living_in)
                    && filled($member->state_living_in)
                    && filled($member->city_living_in),
            ],
            'astro' => [
                'title' => 'Astro & Kundali',
                'completed' => filled($member->manglik) && filled($member->birth_place),
            ],
            'education' => [
                'title' => 'Education & Career',
                'completed' => filled($member->about_my_education)
                    && filled($member->education)
                    && filled($member->any_other_qualifications)
                    && filled($member->employed_in)
                    && filled($member->organization_name)
                    && filled($member->job_location)
                    && filled($member->occupation)
                    && filled($member->annual_income),
            ],
            'family' => [
                'title' => 'Family',
                'completed' => filled($member->about_family)
                    && filled($member->family_status)
                    && filled($member->native_place)
                    && filled($member->father_name)
                    && filled($member->father_occupation)
                    && filled($member->mother_name)
                    && filled($member->mother_occupation)
                    && filled($member->no_of_brothers)
                    && filled($member->married_brothers)
                    && filled($member->no_of_sisters)
                    && filled($member->married_sisters),
            ],
            'lifestyle' => [
                'title' => 'Lifestyle',
                'completed' => filled($member->diet)
                    && filled($member->is_smoking)
                    && filled($member->is_drinking)
                    && filled($member->any_disability)
                    && ($member->any_disability !== 'Yes' || filled($member->disability_detail)),
            ],
            'religion' => [
                'title' => 'Religion & Community',
                'completed' => filled($member->gotra) && filled($member->sub_cast),
            ],
            'preferences' => [
                'title' => 'Partner Preference',
                'completed' => filled($member->looking_for)
                    && filled($member->partner_age_from)
                    && filled($member->partner_age_to)
                    && filled($member->partner_height_from)
                    && filled($member->partner_height_to)
                    && filled($member->partner_religion)
                    && filled($member->partner_cast)
                    && filled($member->partner_mothertongue)
                    && filled($member->partner_education)
                    && filled($member->partner_occupation)
                    && filled($member->partner_annual_income_from)
                    && filled($member->partner_annual_income_to)
                    && filled($member->is_partner_smoking)
                    && filled($member->is_partner_drinking)
                    && filled($member->partner_diet)
                    && filled($member->is_partner_manglik)
                    && filled($member->about_my_partner),
            ],
            'contact' => [
                'title' => 'Contact Info',
                'completed' => filled($member->mobile_number) && filled($member->email),
            ],
        ];
    }

    public function summary(object $member): array
    {
        $sections = $this->sections($member);
        $completed = collect($sections)->where('completed', true)->count();
        $total = count($sections);

        return [
            'completedSections' => $completed,
            'totalSections' => $total,
            'profileCompletion' => (int) round($completed / $total * 100),
        ];
    }

    public function percentage(object $member): int
    {
        return $this->summary($member)['profileCompletion'];
    }
}
