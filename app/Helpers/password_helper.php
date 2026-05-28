<?php

if (! function_exists('make_password_hash')) {
    function make_password_hash(string $password): string
    {
        return password_hash($password, password_hash_algorithm());
    }
}

if (! function_exists('check_password_hash')) {
    function check_password_hash(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

if (! function_exists('password_hash_algorithm')) {
    function password_hash_algorithm(): string
    {
        return match ((string) (env('PASSWORD_ALGORITHM') ?: 'PASSWORD_DEFAULT')) {
            'PASSWORD_BCRYPT' => PASSWORD_BCRYPT,
            'PASSWORD_ARGON2I' => PASSWORD_ARGON2I,
            'PASSWORD_ARGON2ID' => PASSWORD_ARGON2ID,
            default => PASSWORD_DEFAULT,
        };
    }
}
