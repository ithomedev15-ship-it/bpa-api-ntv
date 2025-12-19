<?php

namespace App\Middleware;

use App\Services\ApiKeyService;

class ApiKeyMiddleware
{
    public function handle(): void
    {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

        if ($apiKey === '') {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'API Key wajib dikirim'
            ]);
            exit;
        }

        // Buat service langsung (tanpa DI)
        $service = new ApiKeyService();

        if (!$service->validate($apiKey)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'API Key tidak valid atau non aktif'
            ]);
            exit;
        }

        // kalau valid → lanjut request
    }
}
