<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class HeightOptions
{
    public static function sorted(Collection $heights): Collection
    {
        return $heights->sortBy(function ($height): int {
            $label = HeightFormatter::format($height->height_value ?? $height->height);

            if (preg_match('/^(\d+) ft (\d+) in$/', $label, $parts)) {
                return (int) $parts[1] * 12 + (int) $parts[2];
            }

            return PHP_INT_MAX;
        })->values();
    }
}
