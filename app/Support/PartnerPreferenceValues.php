<?php

namespace App\Support;

class PartnerPreferenceValues
{
    public const FIELDS = [
        'looking_for', 'partner_religion', 'partner_cast',
        'partner_mothertongue', 'partner_education', 'partner_occupation',
    ];

    public static function rules(): array
    {
        $rules = [];

        foreach (self::FIELDS as $field) {
            $rules[$field] = ['nullable', 'array', function ($attribute, $value, $fail) {
                if (is_array($value) && count(array_filter($value, 'is_string')) === count($value)
                    && mb_strlen(implode(',', $value)) > 255) {
                    $fail('The selected preferences must fit within 255 characters.');
                }
            }];
            $rules["$field.*"] = ['required', 'string', 'max:255', 'distinct'];
        }

        return $rules;
    }

    public static function normalize(array $values): array
    {
        foreach (self::FIELDS as $field) {
            if (isset($values[$field]) && is_array($values[$field])) {
                $values[$field] = implode(',', $values[$field]);
            }
        }

        return $values;
    }

    public static function selection(array|string|null $value): array
    {
        $values = is_array($value) ? $value : explode(',', $value ?? '');

        return array_values(array_filter(array_map('trim', $values), fn ($item) => $item !== ''));
    }
}
