<?php

namespace App\Helpers;

use DateTime;
use InvalidArgumentException;

class ApiKeyGenerator
{
    const PREFIX = 'APPBPASFT';

    /**
     * Timestamp format:
     * Y-m-d-H-i-s-v (milisecond)
     */
    public static function toJulianDate(string $timestamp): int
    {
        $dt = DateTime::createFromFormat(
            'Y-m-d-H-i-s-v',
            $timestamp
        );

        if (!$dt) {
            throw new InvalidArgumentException(
                'Format timestamp tidak valid (Y-m-d-H-i-s-v)'
            );
        }

        // Julian Date hanya butuh tanggal
        return gregoriantojd(
            (int) $dt->format('m'),
            (int) $dt->format('d'),
            (int) $dt->format('Y')
        );
    }

    public static function generate(string $username, string $timestamp): string
    {
        $username = strtoupper(trim($username));

        if ($username === '') {
            throw new InvalidArgumentException(
                'Username tidak boleh kosong'
            );
        }

        // Julian dari timestamp (abaikan jam & ms)
        $julian = self::toJulianDate($timestamp);

        // RAW STRING (timestamp ms ikut dihitung ke hash)
        $raw = self::PREFIX
            . $username
            . $julian
            . $timestamp;

        // MD5 sebanyak panjang username
        $hashCount = strlen($username);
        $hash = $raw;

        for ($i = 0; $i < $hashCount; $i++) {
            $hash = md5($hash);
        }

        return $hash;
    }
}