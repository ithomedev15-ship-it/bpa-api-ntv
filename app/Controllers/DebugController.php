<?php

namespace App\Controllers;

use App\Helpers\UrlKey;
use App\Helpers\Response;

class DebugController
{
    public function generateUrlKey(): void
    
    {
        // ambil input (raw JSON / form)
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!is_array($payload)) {
            $payload = $_POST;
        }

        $username = $payload['username']
            ?? $payload['USERNAME']
            ?? null;

        if (!$username) {
            Response::error('username wajib diisi', 422);
        }

        $key = UrlKey::encrypt($username);

        Response::success([
            'username'   => $username,
            'url_key'    => $key,
            'expired_in' => config('security.url_key_expire')
        ]);
    }
}
