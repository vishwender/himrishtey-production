<?php

namespace App\Website\Services;

use App\Support\HeightFormatter;
use Carbon\Carbon;

final class ProfileShareData
{
    public static function sections(object $profile): array
    {
        // Explicit public fields only: never serialize the member record or contact details.
        $fields = [
            'Personal details' => [
                'profile_id' => 'Profile ID',
                'profile_created_for' => 'Created by',
                'gender' => 'Gender',
                'marital_status' => 'Marital status',
                'religion' => 'Religion',
                'cast' => 'Community',
                'sub_cast' => 'Sub-community',
                'gotra' => 'Gotra',
                'mother_tongue' => 'Mother tongue',
                'native_place' => 'Native place',
                'city_living_in' => 'City',
                'state_living_in' => 'State',
                'country_living_in' => 'Country',
                'birth_place' => 'Birth place',
                'manglik' => 'Manglik',
            ],
            'Education & career' => [
                'education' => 'Education',
                'about_my_education' => 'About my education',
                'any_other_qualifications' => 'Other qualifications',
                'employed_in' => 'Employed in',
                'occupation' => 'Occupation',
                'organization_name' => 'Organization',
                'job_location' => 'Job location',
                'annual_income' => 'Annual income',
            ],
            'Family details' => [
                'about_family' => 'About family',
                'family_type' => 'Family type',
                'father_occupation' => 'Father’s occupation',
                'mother_occupation' => 'Mother’s occupation',
                'no_of_brothers' => 'Brothers',
                'married_brothers' => 'Married brothers',
                'no_of_sisters' => 'Sisters',
                'married_sisters' => 'Married sisters',
            ],
            'Lifestyle' => [
                'diet' => 'Diet',
                'is_smoking' => 'Smoking',
                'is_drinking' => 'Drinking',
                'any_disability' => 'Disability',
            ],
            'Partner preferences' => [
                'about_my_partner' => 'About my partner',
                'looking_for' => 'Marital status',
                'partner_religion' => 'Religion',
                'partner_mothertongue' => 'Mother tongue',
                'partner_cast' => 'Community',
                'is_partner_manglik' => 'Manglik',
                'partner_education' => 'Education',
                'partner_occupation' => 'Occupation',
                'partner_annual_income_from' => 'Annual income from (lakhs)',
                'partner_annual_income_to' => 'Annual income to (lakhs)',
            ],
        ];
        $sections = [];
        foreach ($fields as $section => $mapping) {
            foreach ($mapping as $field => $label) {
                $sections[$section][$label] = self::value($profile->$field ?? null);
            }
        }
        $birth = null;
        try {
            $birth = !empty($profile->birth_date_time) ? Carbon::parse($profile->birth_date_time) : null;
        } catch (\Throwable) {
            // Old records may not have a valid date of birth.
        }
        $sections['Personal details']['Age'] = self::value($profile->age_years ?? $birth?->age);
        $sections['Personal details']['Height'] = HeightFormatter::format($profile->height ?? null, 'Not specified');
        $sections['Personal details']['Date of birth'] = $birth?->format('j F Y') ?? 'Not specified';
        $sections['Personal details']['Time of birth'] = $birth?->format('g:i A') ?? 'Not specified';
        $sections['Partner preferences']['Age range'] = self::value($profile->partner_age_from ?? null).' – '.self::value($profile->partner_age_to ?? null);
        $sections['Partner preferences']['Height range'] = HeightFormatter::formatPartnerRange($profile->partner_height_from ?? null, 'Not specified').' – '.HeightFormatter::formatPartnerRange($profile->partner_height_to ?? null, 'Not specified');

        return ['About me' => ['About' => self::value($profile->about_me ?? null)]] + $sections;
    }

    public static function text(object $profile, string $url): string
    {
        $lines = ['*'.self::value($profile->full_name ?? null).'*', 'View profile: '.$url];
        foreach (self::sections($profile) as $heading => $fields) {
            $lines[] = "\n*{$heading}*";
            foreach ($fields as $label => $value) {
                $lines[] = $label.': '.$value;
            }
        }

        return implode("\n", $lines);
    }

    private static function value(mixed $value): string
    {
        $text = trim(strip_tags((string) $value));

        return $text === '' ? 'Not specified' : $text;
    }
}
