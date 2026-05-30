<?php

if (! function_exists('digits_only')) {
    function digits_only(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }
}

if (! function_exists('format_cpf')) {
    function format_cpf(string $value): string
    {
        $value = digits_only($value);

        if ($value === '' || strlen($value) !== 11) {
            return $value;
        }

        return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3) . '-' . substr($value, 9, 2);
    }
}

if (! function_exists('format_phone')) {
    function format_phone(string $value): string
    {
        $value = digits_only($value);

        if ($value === '') {
            return '';
        }

        if (strlen($value) <= 10) {
            return '(' . substr($value, 0, 2) . ') ' . substr($value, 2, 4) . '-' . substr($value, 6);
        }

        return '(' . substr($value, 0, 2) . ') ' . substr($value, 2, 5) . '-' . substr($value, 7);
    }
}
