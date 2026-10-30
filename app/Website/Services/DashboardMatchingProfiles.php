<?php

namespace App\Website\Services;

use App\Models\Member as ApiMember;
use App\Services\ProfileMatchingService;
use App\Support\HeightFormatter;
use App\Website\Models\Member;
use Illuminate\Database\Eloquent\Builder;

final class DashboardMatchingProfiles
{
    public function __construct(private ProfileMatchingService $matching) {}

    public function query(Member $member, array $columns = ['members.*']): Builder
    {
        $preferences = $this->preferences($member);
        $percentage = $this->hasPreferences($member)
            ? 'CASE WHEN match_score > 100 THEN 100 ELSE match_score END'
            : 'NULL';

        return $this->matching->rankedQuery($preferences, columns: $columns)
            ->where('match_score', '>', 70)
            ->when(! $this->hasPreferences($member), fn ($query) => $query->whereRaw('1 = 0'))
            ->select('ranked_profiles.*')
            ->selectRaw($percentage.' AS match_percentage');
    }

    public function hasPreferences(Member $member): bool
    {
        $preferences = $this->preferences($member);
        foreach (['partner_age_from', 'partner_age_to', 'partner_height_from', 'partner_height_to',
            'partner_religion', 'partner_cast', 'partner_education', 'partner_mothertongue',
            'partner_occupation', 'partner_diet', 'is_partner_manglik', 'is_partner_smoking',
            'is_partner_drinking', 'partner_state'] as $field) {
            foreach (explode(',', (string) $preferences->$field) as $value) {
                if (! in_array(strtolower(trim($value)), ['', 'any'], true)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function preferences(Member $member): ApiMember
    {
        $preferences = new ApiMember;
        $preferences->setRawAttributes($member->getAttributes());

        // Imported profiles use zero to mean that no age range was selected.
        foreach (['partner_age_from', 'partner_age_to'] as $field) {
            if (! is_numeric($preferences->$field) || (float) $preferences->$field <= 0) {
                $preferences->$field = null;
            }
        }

        // The website saves height slider positions; the API scorer expects heights.
        foreach (['partner_height_from', 'partner_height_to'] as $field) {
            $preferences->$field = is_numeric($member->$field) && (float) $member->$field > 0
                ? HeightFormatter::formatPartnerRange($member->$field)
                : null;
        }

        return $preferences;
    }
}
