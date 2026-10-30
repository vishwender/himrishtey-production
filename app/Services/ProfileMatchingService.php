<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProfileMatchingService
{
    public const CARD_FIELDS = [
        'id', 'profile_id', 'full_name', 'birth_date_time', 'gender', 'height',
        'religion', 'mother_tongue', 'cast', 'marital_status', 'education',
        'occupation', 'annual_income', 'country_living_in', 'state_living_in',
        'city_living_in', 'photo', 'profile_completed', 'member_type',
    ];

    public function eligibleQuery(Member $member): Builder
    {
        $gender = strtolower(trim((string) $member->gender));

        return Member::query()
            ->when(! in_array($gender, ['male', 'female'], true), fn ($q) => $q->whereRaw('1 = 0'))
            ->where('id', '!=', $member->id)
            ->whereRaw('LOWER(TRIM(gender)) = ?', [$gender === 'male' ? 'female' : 'male'])
            ->whereRaw("LOWER(TRIM(active)) = 'yes'")
            ->where(fn ($q) => $q->whereNull('profile_hide')->orWhere('profile_hide', '')
                ->orWhereRaw("LOWER(TRIM(profile_hide)) = 'no'"));
    }

    /** Rank in SQL so only the requested cards are hydrated. */
    public function rankedQuery(Member $member, ?Carbon $asOf = null): Builder
    {
        $asOf ??= Carbon::today();
        $query = $this->eligibleQuery($member)->select(self::CARD_FIELDS);
        $parts = ['0'];
        $bindings = [];
        foreach ([
            'religion' => ['partner_religion', 15], 'cast' => ['partner_cast', 15],
            'education' => ['partner_education', 10], 'mother_tongue' => ['partner_mothertongue', 10],
            'occupation' => ['partner_occupation', 5], 'diet' => ['partner_diet', 5],
            'manglik' => ['is_partner_manglik', 5], 'is_smoking' => ['is_partner_smoking', 5],
            'is_drinking' => ['is_partner_drinking', 5], 'state_living_in' => ['partner_state', 5],
        ] as $column => [$preference, $weight]) {
            $values = array_values(array_filter(array_map(fn ($v) => strtolower(trim($v)),
                explode(',', (string) $member->$preference)), fn ($v) => $v !== ''));
            if ($values === []) {
                continue;
            }
            $column = $query->getQuery()->getGrammar()->wrap($column);
            $conditions = [];
            foreach ($values as $value) {
                // LOCATE/INSTR treat '%' and '_' literally, like PHP str_contains.
                $conditions[] = "(INSTR(LOWER(TRIM($column)), ?) > 0 OR INSTR(?, LOWER(TRIM($column))) > 0)";
                array_push($bindings, $value, $value);
            }
            $parts[] = "CASE WHEN TRIM(COALESCE($column, '')) <> '' AND (".implode(' OR ', $conditions).") THEN $weight ELSE 0 END";
        }
        if ($this->hasValue($member->partner_age_from) || $this->hasValue($member->partner_age_to)) {
            $from = $this->hasValue($member->partner_age_from) ? (int) $member->partner_age_from : 18;
            $to = $this->hasValue($member->partner_age_to) ? (int) $member->partner_age_to : 100;
            $parts[] = 'CASE WHEN birth_date_time >= ? AND birth_date_time < ? THEN 20 ELSE 0 END';
            $bindings[] = $asOf->copy()->subYears($to + 1)->addDay()->format('Y-m-d');
            $bindings[] = $asOf->copy()->subYears($from)->addDay()->format('Y-m-d');
        }
        if ($this->hasValue($member->partner_height_from) || $this->hasValue($member->partner_height_to)) {
            // Evaluate the small distinct legacy height domain, never full member records.
            $heights = Member::query()->whereNotNull('height')->distinct()->pluck('height')
                ->filter(fn ($height) => $this->matchesHeight($height, $member->partner_height_from, $member->partner_height_to))
                ->values()->all();
            if ($heights !== []) {
                $parts[] = 'CASE WHEN height IN ('.implode(',', array_fill(0, count($heights), '?')).') THEN 10 ELSE 0 END';
                array_push($bindings, ...$heights);
            }
        }
        $query->selectRaw('('.implode(' + ', $parts).') AS match_score', $bindings)
            ->selectRaw("CASE WHEN LOWER(TRIM(promoted)) = 'yes' THEN 1 ELSE 0 END AS promoted_rank");

        // A derived table makes parameterized score expressions cursor-compatible.
        return Member::query()->fromSub($query->toBase(), 'ranked_profiles')
            ->orderByDesc('match_score')->orderByDesc('promoted_rank')->orderByDesc('id');
    }

    /** Preserve the existing Home response while limiting the database result. */
    public function getMatches(Member $member, int $limit = 10): Collection
    {
        return $this->rankedQuery($member)->limit(max(0, $limit))->get()
            ->map(fn ($candidate) => $this->card($candidate, true));
    }

    public function card(Member $member, bool $includeMatch = false): array
    {
        $data = $member->only(array_values(array_diff(self::CARD_FIELDS, ['birth_date_time'])));
        $data['age'] = $this->calculateAge($member->birth_date_time);
        $data['profile_completed'] = (int) $member->profile_completed;
        if ($includeMatch) {
            $data['match_percentage'] = min((int) $member->match_score, 100);
        }

        return $data;
    }

    /**
     * Check whether a value is actually populated.
     */
    private function hasValue($value): bool
    {
        return $value !== null &&
            trim((string) $value) !== '';
    }

    /**
     * Check candidate height against preference range.
     *
     * Supports:
     * 5ft 6in
     * 5ft
     * 5.5
     * 5.6
     */
    private function matchesHeight(
        $candidateHeight,
        $heightFrom,
        $heightTo
    ): bool {

        if (! $this->hasValue($candidateHeight)) {
            return false;
        }

        if (
            ! $this->hasValue($heightFrom) &&
            ! $this->hasValue($heightTo)
        ) {
            return false;
        }

        $candidateHeightInches =
            $this->heightToInches($candidateHeight);

        if ($candidateHeightInches === null) {
            return false;
        }

        $from = $this->hasValue($heightFrom)
            ? $this->heightToInches($heightFrom)
            : null;

        $to = $this->hasValue($heightTo)
            ? $this->heightToInches($heightTo)
            : null;

        if (
            $from !== null &&
            $candidateHeightInches < $from
        ) {
            return false;
        }

        if (
            $to !== null &&
            $candidateHeightInches > $to
        ) {
            return false;
        }

        return true;
    }

    /**
     * Convert height to inches.
     */
    private function heightToInches($height): ?float
    {
        if ($height === null || $height === '') {
            return null;
        }

        $height = strtolower(
            trim((string) $height)
        );

        /*
        |--------------------------------------------------------------------------
        | 5ft 6in
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/(\d+(?:\.\d+)?)\s*ft\s*(\d+(?:\.\d+)?)?\s*in?/',
                $height,
                $matches
            )
        ) {

            $feet = (float) $matches[1];

            $inches = isset($matches[2])
                ? (float) $matches[2]
                : 0;

            return ($feet * 12) + $inches;
        }

        /*
        |--------------------------------------------------------------------------
        | 5'6"
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/(\d+(?:\.\d+)?)\s*[\'′]\s*(\d+(?:\.\d+)?)?\s*["″]?/',
                $height,
                $matches
            )
        ) {

            $feet = (float) $matches[1];

            $inches = isset($matches[2])
                ? (float) $matches[2]
                : 0;

            return ($feet * 12) + $inches;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric height
        |--------------------------------------------------------------------------
        |
        | Existing partner preference fields are stored as DOUBLE,
        | e.g. 5.5.
        |
        */

        if (is_numeric($height)) {

            $value = (float) $height;

            /*
             * 5.5 means 5 feet 5 inches in the existing system,
             * rather than 5.5 literal feet.
             */
            $feet = floor($value);
            $decimal = $value - $feet;

            if ($decimal > 0) {

                $inches = round($decimal * 10);

                return ($feet * 12) + $inches;
            }

            return $feet * 12;
        }

        return null;
    }

    /**
     * Calculate age from stored birth date.
     */
    private function calculateAge(
        ?string $birthDate
    ): ?int {

        if (! $this->hasValue($birthDate)) {
            return null;
        }

        try {

            $date = Carbon::parse($birthDate);

            return $date->age;
        } catch (\Throwable $e) {

            return null;
        }
    }
}
