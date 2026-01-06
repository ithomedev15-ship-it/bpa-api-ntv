<?php

namespace App\Helpers;

class Auth
{
    /**
     * Username login (STRING)
     */
    public static function username(): ?string
    {
        return isset($_SERVER['AUTH_USER']) && is_string($_SERVER['AUTH_USER'])
            ? $_SERVER['AUTH_USER']
            : null;
    }

    /**
     * Kode user / primary key
     */
    public static function kodeUser(): ?string
    {
        return isset($_SERVER['AUTH_USER_CODE']) && is_string($_SERVER['AUTH_USER_CODE'])
            ? $_SERVER['AUTH_USER_CODE']
            : null;
    }

    /**
     * Check login status
     */
    public static function check(): bool
    {
        return self::username() !== null;
    }
}
