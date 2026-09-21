<?php

namespace App\Website\Services;

class ProfileUnlockPricing
{
    public static function priceForViewCount(int $viewedProfileCount): int
    {
        return match (true) {
            $viewedProfileCount <= 20 => 3,
            $viewedProfileCount <= 50 => 8,
            $viewedProfileCount <= 100 => 15,
            $viewedProfileCount <= 200 => 25,
            default => 50,
        };
    }
}
