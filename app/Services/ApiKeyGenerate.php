<?php

namespace App\Services;

use InvalidArgumentException;

class ApiKeyGenerate
{
    const APP_SECRET = 'APPBPASAFETY';

    public static function generateUserMd5(string $username): string
    {
        $username = trim($username);

        if ($username === '') {
            throw new InvalidArgumentException('Username tidak boleh kosong');
        }

        $baseHash  = md5(self::APP_SECRET);
        $finalHash = md5($baseHash . $username);

        return $finalHash;
    }
}
