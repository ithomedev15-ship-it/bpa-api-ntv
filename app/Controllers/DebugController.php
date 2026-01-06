<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\ApiKeyGenerator;

class DebugController
{
    public function generateApiKey(): void
    {
        $username  = $_GET['username'] ?? '';
        $timestamp = $_GET['timestamp'] ?? '';

        if (!$username || !$timestamp) {
            Response::error(
                'username & timestamp wajib',
                400
            );
        }

        Response::success([
            'api_key' => ApiKeyGenerator::generate($username, $timestamp),
            'length'  => strlen($username)
        ]);
    }
}