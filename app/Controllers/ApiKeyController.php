<?php

namespace App\Controllers;

use App\Services\ApiKeyService;
use App\Helpers\Response;
use Throwable;

class ApiKeyController
{
    public function generate(): void
    {
        try {
            $payload = json_decode(file_get_contents('php://input'), true);

            if (!is_array($payload)) {
                $payload = $_POST;
            }

            $username = $payload['username']
                ?? $payload['CLIENT_NAME']
                ?? null;

            if (!$username) {
                Response::error('username wajib diisi', 422);
            }

            $service = new ApiKeyService();
            $apiKey  = $service->generateAndStore($username);

            Response::success([
                'client_name' => $username,
                'api_key'     => $apiKey,
            ]);

        } catch (Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
