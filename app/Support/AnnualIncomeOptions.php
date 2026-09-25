<?php

namespace App\Support;

final class AnnualIncomeOptions
{
    public static function labels(): array
    {
        $options = [];
        for ($from = 0; $from < 50; $from++) {
            $options[] = $from.'-'.($from + 1).' lakhs';
        }
        $options[] = 'more than 50 lakhs';

        return $options;
    }

    public static function filter($query, ?string $from, ?string $to): void
    {
        $labels = self::labels();
        // Numeric dropdown values use the same labels as Edit Profile:
        // 3 lakhs corresponds to the existing stored 2-3 lakhs option.
        $index = static function (?string $value) use ($labels) {
            if ($value !== null && ctype_digit($value) && (int) $value >= 1 && (int) $value <= 50) {
                return (int) $value - 1;
            }
            return array_search($value, $labels, true);
        };
        $lower = $index($from);
        $upper = $index($to);

        if ($lower === false && $upper === false) {
            return;
        }

        $lower = $lower === false ? 0 : $lower;
        $upper = $upper === false ? 50 : $upper;
        $query->whereIn('annual_income', $lower <= $upper ? array_slice($labels, $lower, $upper - $lower + 1) : []);
    }
}
