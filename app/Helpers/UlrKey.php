<?php

namespace App\Helpers;

class UrlKey
{
    private static function iv(string $secret): string
    {
        // AES-256-CBC butuh IV 16 byte
        return substr(hash('sha256', $secret), 0, 16);
    }

    public static function encrypt(string $username): string
    {
        $payload = json_encode([
            'u' => $username,
            't' => time(),
        ], JSON_THROW_ON_ERROR);

        $secret = config('security.url_key_secret');

        $encrypted = openssl_encrypt(
            $payload,
            'AES-256-CBC',
            $secret,
            OPENSSL_RAW_DATA,
            self::iv($secret)
        );

        if ($encrypted === false) {
            throw new \RuntimeException('Failed to encrypt URL key');
        }

        // URL-safe base64
        return rtrim(
            strtr(base64_encode($encrypted), '+/', '-_'),
            '='
        );
    }

    public static function decrypt(string $key): array
    {
        $secret = config('security.url_key_secret');

        // kembalikan padding base64
        $key = strtr($key, '-_', '+/');
        $key .= str_repeat('=', (4 - strlen($key) % 4) % 4);

        $decoded = base64_decode($key, true);

        if ($decoded === false) {
            throw new \RuntimeException('Invalid base64 URL key');
        }

        $json = openssl_decrypt(
            $decoded,
            'AES-256-CBC',
            $secret,
            OPENSSL_RAW_DATA,
            self::iv($secret)
        );

        if ($json === false) {
            throw new \RuntimeException('Invalid or tampered URL key');
        }

        $data = json_decode($json, true);

        if (!is_array($data) || !isset($data['u'], $data['t'])) {
            throw new \RuntimeException('Invalid URL key payload');
        }

        return $data;
    }
}
