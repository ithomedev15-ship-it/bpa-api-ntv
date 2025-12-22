<?php

namespace App\Helpers;

class GlobalKey
{
    public static function generate(): string
    {
        $key   = config('security.global_key_raw');
        $round = (int) config('security.global_key_round');

        for ($i = 0; $i < $round; $i++) {
            $key = md5($key);
        }

        return $key;
    }

    public static function validate(string $dbKey): bool
    {
        return hash_equals(self::generate(), $dbKey);
    }
}