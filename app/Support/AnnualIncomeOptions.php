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
        $lower = array_search($from, $labels, true);
        $upper = array_search($to, $labels, true);

        if ($lower === false && $upper === false) {
            return;
        }

        $lower = $lower === false ? 0 : $lower;
        $upper = $upper === false ? 50 : $upper;
        $query->whereIn('annual_income', $lower <= $upper ? array_slice($labels, $lower, $upper - $lower + 1) : []);
    }
}
