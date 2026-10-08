<?php

namespace App\Support;

class DisabilityValue
{
    public static function normalize(array $values): array
    {
        if (($values['any_disability'] ?? null) === 'Yes') {
            $values['any_disability'] = trim((string) $values['health_info']);
        } elseif (($values['any_disability'] ?? null) === 'No') {
            $values['health_info'] = null;
        }

        return $values;
    }

    public static function selection(?string $value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '' : (strcasecmp($value, 'No') === 0 ? 'No' : 'Yes');
    }

    public static function description(?string $value, ?string $legacyDescription = null): string
    {
        $value = trim((string) $value);
        if (self::selection($value) !== 'Yes') {
            return '';
        }

        return strcasecmp($value, 'Yes') === 0 ? (string) $legacyDescription : $value;
    }
}
