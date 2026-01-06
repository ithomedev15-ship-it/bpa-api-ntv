<?php

namespace App\Helpers;

class Auth
{
    public static function username(): ?string
    {
        return $_SERVER['AUTH_USER']['username'] ?? null;
    }

    public static function kodeUser(): ?string
    {
        return $_SERVER['AUTH_USER']['kode_user'] ?? null;
    }
}
