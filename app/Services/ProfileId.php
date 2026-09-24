<?php

namespace App\Services;

class ProfileId
{
    public static function prefix(?string $site): ?string
    {
        return match ($site) {
            'main', 'himrishtey', 'himrishtey_main', 'himrishteymain_base' => 'HIM',
            'gallpakki', 'himrishteymain_gallpakki' => 'PB',
            'dogririshtey', 'himrishteymain_dogririshtey' => 'JR',
            'devbhoomi', 'himrishteymain_devbhoomi' => 'DR',
            default => null,
        };
    }

    public static function forSite(?string $site, int $memberId): string
    {
        $prefix = self::prefix($site);

        if ($prefix === null) {
            throw new \RuntimeException(
                "No profile ID prefix configured for site: {$site}"
            );
        }

        $number = match ($prefix) {
            'HIM' => 10000 + $memberId,
            'PB'  => 10000 + $memberId,
            'DR'  => 110000 + $memberId,
            'JR'  => 10000 + $memberId,

            default => throw new \RuntimeException(
                "No profile ID numbering rule configured for prefix: {$prefix}"
            ),
        };

        return $prefix . $number;
    }
}
