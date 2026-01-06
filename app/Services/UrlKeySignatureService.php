<?php

namespace App\Services;

use App\Helpers\UrlKeySignature;
use Exception;

class UrlKeySignatureService
{
    public function validateFromUrl(
    string $xModul,
    string $username,
    string $clientKey
): void {
    if ($xModul === '' || $username === '' || $clientKey === '') {
        throw new Exception('Data URL Signature tidak lengkap');
    }

    $serverKey = UrlKeySignature::generate(
        $xModul,
        $username
    );

    if (!hash_equals($serverKey, $clientKey)) {
        throw new Exception('URL Key tidak valid');
    }
}
}
