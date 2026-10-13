<?php

namespace App\Services;

use Illuminate\Http\Request;

class AdvancedMemberSearch
{
    public function apply($query, Request $request): void
    {
        $request->validate([
            'profile_id' => ['nullable', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'profile_created_for' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'age_from' => ['nullable', 'integer', 'min:18', 'max:100'],
            'age_to' => ['nullable', 'integer', 'min:18', 'max:100'],
            'religion' => ['nullable', 'string', 'max:255'],
            'mother_tongue' => ['nullable', 'string', 'max:255'],
            'cast' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:255'],
            'manglik' => ['nullable', 'string', 'max:255'],
            'no_of_child' => ['nullable', 'integer', 'min:0'],
            'education' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'employed_in' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'job_location' => ['nullable', 'string', 'max:255'],
            'annual_income_from' => ['nullable', 'string', 'max:255'],
            'annual_income_to' => ['nullable', 'string', 'max:255'],
            'country_living_in' => ['nullable', 'string', 'max:255'],
            'state_living_in' => ['nullable', 'string', 'max:255'],
            'city_living_in' => ['nullable', 'string', 'max:255'],
            'family_type' => ['nullable', 'string', 'max:255'],
            'family_status' => ['nullable', 'string', 'max:255'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'family_income' => ['nullable', 'string', 'max:255'],
            'diet' => ['nullable', 'string', 'max:255'],
            'is_drinking' => ['nullable', 'string', 'max:255'],
            'is_smoking' => ['nullable', 'string', 'max:255'],
            'any_disability' => ['nullable', 'string', 'max:255'],
            'partner_age_from' => ['nullable', 'integer', 'min:18', 'max:100'],
            'partner_age_to' => ['nullable', 'integer', 'min:18', 'max:100'],
            'partner_religion' => ['nullable', 'string', 'max:255'],
            'partner_cast' => ['nullable', 'string', 'max:255'],
            'partner_education' => ['nullable', 'string', 'max:255'],
            'partner_mothertongue' => ['nullable', 'string', 'max:255'],
            'partner_occupation' => ['nullable', 'string', 'max:255'],
            'partner_country' => ['nullable', 'string', 'max:255'],
            'partner_state' => ['nullable', 'string', 'max:255'],
            'partner_city' => ['nullable', 'string', 'max:255'],
            'partner_diet' => ['nullable', 'string', 'max:255'],
            'is_partner_smoking' => ['nullable', 'string', 'max:255'],
            'is_partner_drinking' => ['nullable', 'string', 'max:255'],
            'is_partner_manglik' => ['nullable', 'string', 'max:255'],
            'partner_height_from' => ['nullable', 'string', 'max:255'],
            'partner_height_to' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'string', 'max:255'],
            'member_type' => ['nullable', 'string', 'max:255'],
            'plan_id' => ['nullable', 'integer', 'min:0'],
            'is_trusted' => ['nullable', 'string', 'max:255'],
            'promoted' => ['nullable', 'string', 'max:255'],
            'profile_hide' => ['nullable', 'string', 'max:255'],
            'register_through' => ['nullable', 'string', 'max:255'],
            'relationship_manager' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['age', 'partner_age'] as $prefix) {
            if ($request->filled($prefix.'_from') && $request->filled($prefix.'_to')
                && (int) $request->input($prefix.'_from') > (int) $request->input($prefix.'_to')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $prefix.'_to' => 'The maximum age must be at least the minimum age.',
                ]);
            }
        }

        \App\Support\AnnualIncomeOptions::filter($query, $request->input('annual_income_from'), $request->input('annual_income_to'));

        /*
    |--------------------------------------------------------------------------
    | Basic Search
    |--------------------------------------------------------------------------
    */

        foreach (['designation', 'organization_name', 'job_location', 'father_occupation', 'mother_occupation', 'family_income'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, 'like', '%'.trim($request->input($field)).'%');
            }
        }

        if ($request->filled('any_disability')) {
            if ($request->input('any_disability') === 'Yes') {
                $query->whereNotNull('any_disability')->whereNotIn('any_disability', ['', 'No', 'no']);
            } else {
                $query->where('any_disability', 'No');
            }
        }

        if ($request->filled('profile_id')) {
            $query->where(
                'profile_id',
                'like',
                '%' . trim($request->profile_id) . '%'
            );
        }

        if ($request->filled('full_name')) {
            $query->where(
                'full_name',
                'like',
                '%' . trim($request->full_name) . '%'
            );
        }

        if ($request->filled('email')) {
            $query->where(
                'email',
                'like',
                '%' . trim($request->email) . '%'
            );
        }

        if ($request->filled('mobile_number')) {
            $query->where(
                'mobile_number',
                'like',
                '%' . trim($request->mobile_number) . '%'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Personal
    |--------------------------------------------------------------------------
    */

        foreach (
            [
                'profile_created_for',
                'gender',
                'religion',
                'mother_tongue',
                'cast',
                'marital_status',
                'manglik',
                'education',
                'occupation',
                'employed_in',
                'country_living_in',
                'state_living_in',
                'city_living_in',
                'family_type',
                'family_status',
                'is_drinking',
                'is_smoking',
            ] as $field
        ) {

            if ($request->filled($field)) {
                $query->whereRaw("LOWER(TRIM(`$field`)) = ?", [mb_strtolower(trim($request->input($field)))]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Account
    |--------------------------------------------------------------------------
    */

        foreach (
            [
                'member_type',
                'plan_id',
                'no_of_child',
                'register_through',
            ] as $field
        ) {

            if ($request->filled($field)) {
                $query->whereRaw("LOWER(TRIM(`$field`)) = ?", [mb_strtolower(trim($request->input($field)))]);
            }
        }

        foreach (['diet', 'partner_diet'] as $field) {
            if (!$request->filled($field)) continue;
            $aliases = match ($request->input($field)) {
                'Veg' => ['veg', 'pure veg', 'vegetarian'],
                'Non Veg' => ['non veg', 'non-veg', 'nonveg', 'non vegetarian'],
                'Veg & Non Veg' => ['veg & non veg', 'veg & non-veg', 'veg & nonveg', 'veg/nonveg', 'veg/noonveg'],
                default => [mb_strtolower(trim($request->input($field)))],
            };
            $query->whereRaw("LOWER(TRIM(`$field`)) IN (".implode(',', array_fill(0, count($aliases), '?')).")", $aliases);
        }

        $memberScopes = new \App\Models\SiteMember;
        $memberScopes->scopeFilterStatus($query, match ($request->input('active')) {
            'Yes' => 'active', 'No' => 'inactive', default => null,
        });
        if ($request->input('active') === 'Banned') {
            $query->whereRaw('LOWER(TRIM(active)) = ?', ['banned']);
        }
        $memberScopes->scopeFilterVisibility($query, match ($request->input('profile_hide')) {
            'Yes' => 'hidden', 'No' => 'visible', default => null,
        });
        foreach (['is_trusted' => ['yes', 'trusted', 'on', '1'], 'promoted' => ['yes', 'on', '1']] as $field => $truthy) {
            if (!$request->filled($field)) continue;
            $placeholders = implode(',', array_fill(0, count($truthy), '?'));
            if (in_array(strtolower($request->input($field)), ['yes', '1'], true)) {
                $query->whereRaw("LOWER(TRIM(`$field`)) IN ($placeholders)", $truthy);
            } else {
                $query->where(fn ($q) => $q->whereNull($field)->orWhereRaw("LOWER(TRIM(`$field`)) NOT IN ($placeholders)", $truthy));
            }
        }

        if ($request->filled('relationship_manager')) {
            if ($request->relationship_manager === '__unassigned') {
                $query->where(function ($query) {
                    $query->whereNull('relationship_manager')
                        ->orWhere('relationship_manager', '');
                });
            } else {
                $query->where(
                    'relationship_manager',
                    trim((string) $request->relationship_manager)
                );
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Partner Preferences
    |--------------------------------------------------------------------------
    */

        foreach (
            [
                'partner_country',
                'partner_religion',
                'partner_cast',
                'partner_education',
                'partner_mothertongue',
                'partner_occupation',
                'partner_state',
                'partner_city',
                'is_partner_smoking',
                'is_partner_drinking',
                'is_partner_manglik',
            ] as $field
        ) {

            if ($request->filled($field)) {
                $value = $request->input($field);
                if (in_array($field, \App\Support\PartnerPreferenceValues::FIELDS, true)) {
                    $value = mb_strtolower(trim($value));
                    $expression = "LOWER(TRIM(REPLACE(REPLACE(`$field`, ', ', ','), ' ,', ',')))";
                    $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value);
                    $query->where(function ($query) use ($expression, $value, $escaped) {
                        $query->whereRaw("$expression = ?", [$value])
                            ->orWhereRaw("$expression LIKE ? ESCAPE '!'", [$escaped.',%'])
                            ->orWhereRaw("$expression LIKE ? ESCAPE '!'", ['%,'.$escaped])
                            ->orWhereRaw("$expression LIKE ? ESCAPE '!'", ['%,'.$escaped.',%']);
                    });
                } else {
                    $query->whereRaw("LOWER(TRIM(`$field`)) = ?", [mb_strtolower(trim($value))]);
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Age
    |--------------------------------------------------------------------------
    */

        if ($request->filled('age_from')) {

            $date = now()
                ->subYears((int) $request->age_from)
                ->endOfDay();

            $query->where(
                'birth_date_time',
                '<=',
                $date
            );
        }

        if ($request->filled('age_to')) {

            $date = now()
                ->subYears((int) $request->age_to + 1)
                ->addDay()
                ->startOfDay();

            $query->where(
                'birth_date_time',
                '>=',
                $date
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Partner Age
    |--------------------------------------------------------------------------
    */

        if ($request->filled('partner_age_from')) {

            $query->whereRaw('CAST(partner_age_from AS DECIMAL(10,2)) >= 18')->whereRaw('CAST(partner_age_from AS DECIMAL(10,2)) <= ?', [(int) $request->partner_age_from]);
        }

        if ($request->filled('partner_age_to')) {

            $query->whereRaw('CAST(partner_age_to AS DECIMAL(10,2)) >= ?', [(int) $request->partner_age_to]);
        }

        foreach (['partner_height_from' => '<=', 'partner_height_to' => '>='] as $field => $operator) {
            if (!$request->filled($field)) {
                continue;
            }
            $label = \App\Support\HeightFormatter::format($request->input($field));
            if (!preg_match('/^(\d+) ft (\d+) in$/', $label, $parts)) {
                continue;
            }
            $target = (int) $parts[1] * 12 + (int) $parts[2];
            $values = [];
            for ($inches = 48; $inches <= 96; $inches++) {
                if ($operator === '<=' ? $inches > $target : $inches < $target) {
                    continue;
                }
                $feet = intdiv($inches, 12);
                $remainder = $inches % 12;
                array_push($values, "$feet.$remainder", "$feet ft $remainder in", "{$feet}ft {$remainder}in");
                if ($inches >= 54 && $inches <= 81) {
                    $values[] = (string) ($inches - 53);
                }
            }
            $query->whereIn($field, $values);
        }
    }
}
