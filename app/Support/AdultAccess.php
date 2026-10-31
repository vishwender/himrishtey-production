<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Http\Request;

final class AdultAccess
{
    public const MESSAGE = 'HimShaadi is for adults aged 18 and over. A valid adult date of birth is required.';

    public static function appliesTo(Request $request): bool
    {
        return $request->attributes->get('application')?->code === 'himrishtey';
    }

    public static function isAdult(mixed $value): bool
    {
        if ($value instanceof DateTimeInterface) $value = $value->format('Y-m-d');
        if (!is_string($value) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:$|[ T])/', $value, $parts)) return false;
        [$all, $year, $month, $day] = $parts;
        if ((int) $year < 1900 || !checkdate((int) $month, (int) $day, (int) $year)) return false;
        $today = now();
        $age = (int) $today->format('Y') - (int) $year;
        if ($today->format('m-d') < "$month-$day") --$age;
        return $age >= 18;
    }
}
