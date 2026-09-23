<?php

namespace App\Services;

class MemberPhotoFilename
{
    private static int $lastTimestamp = 0;

    public static function make(int $memberId, string $extension): string
    {
        // Microsecond precision keeps batch uploads distinct, even in the same second.
        $timestamp = max((int) floor(microtime(true) * 1000000), self::$lastTimestamp + 1);
        self::$lastTimestamp = $timestamp;

        return 'member-'.$memberId.'-'.$timestamp.'.'.strtolower($extension);
    }
}
