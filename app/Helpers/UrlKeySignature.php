<?php

namespace App\Helpers;

class UrlKeySignature
{
    public const PREFIX_KEY = 'APPGPS2025';

    public static function generate(
        string $xModul,
        string $username
    ): string {
        $xModul   = strtoupper(trim($xModul));
        $username = strtoupper(trim($username));

        $payload = $xModul
            . $username
            . self::PREFIX_KEY;

        $secret = (string) strlen($username);

        return hash_hmac(
            'sha256',
            $payload,
            $secret
        );
    }
}
