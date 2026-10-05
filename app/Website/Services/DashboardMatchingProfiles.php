<?php

namespace App\Website\Services;

use App\Support\HeightFormatter;
use App\Website\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

final class DashboardMatchingProfiles
{
    public function query(Member $member): Builder
    {
        $query = Member::query()
            ->whereRaw('LOWER(TRIM(gender)) != ?', [strtolower(trim((string) $member->gender))])
            ->where('id', '!=', $member->id)
            ->whereRaw('LOWER(TRIM(active)) = ?', ['yes'])
            ->where(function ($query) {
                $query->whereNull('profile_hide')
                    ->orWhereRaw('LOWER(TRIM(profile_hide)) != ?', ['yes']);
            })
            ->whereBetween('birth_date_time', [
                Carbon::today()->subYears((int) $member->partner_age_to + 1)->addDay()->startOfDay(),
                Carbon::today()->subYears((int) $member->partner_age_from)->endOfDay(),
            ]);

        // Partner preferences store slider positions; member heights store feet and inches.
        $from = $this->inches(HeightFormatter::formatPartnerRange($member->partner_height_from));
        $to = $this->inches(HeightFormatter::formatPartnerRange($member->partner_height_to));
        $heights = (clone $query)->select('height')->distinct()->pluck('height')
            ->filter(function ($height) use ($from, $to) {
                $inches = $this->inches(HeightFormatter::format($height));

                return $from !== null && $to !== null && $inches !== null
                    && $inches >= $from && $inches <= $to;
            })->values()->all();
        $query->whereIn('height', $heights);

        foreach ([
            'country_living_in' => 'partner_country',
            'religion' => 'partner_religion',
            'cast' => 'partner_cast',
            'education' => 'partner_education',
            'mother_tongue' => 'partner_mothertongue',
        ] as $column => $preference) {
            $values = array_values(array_filter(array_map('trim', explode(',', (string) $member->$preference)),
                fn ($value) => $value !== ''));

            if (collect($values)->contains(fn ($value) => strcasecmp($value, 'Any') === 0)) {
                // Preserve the existing caste "Any" requirement for a populated caste.
                if ($column === 'cast') {
                    $query->whereNotNull('cast')->where('cast', '!=', '');
                }

                continue;
            }

            if ($values !== []) {
                $query->whereIn($column, $values);
            }
        }

        return $query;
    }

    private function inches(string $height): ?int
    {
        if (! preg_match('/^(\d+) ft (\d+) in$/', $height, $parts)) {
            return null;
        }

        return (int) $parts[1] * 12 + (int) $parts[2];
    }
}
