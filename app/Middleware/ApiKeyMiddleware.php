<?php

namespace App\Middleware;

use Exception;
use App\Helpers\Response;
use App\Services\ApiKeyService;
use App\Repositories\ApiKeyRepository;

class ApiKeyMiddleware
{
    public function handle(): void
    {
        $username  = $_SERVER['HTTP_X_USERNAME'] ?? '';
        $timestamp = $_SERVER['HTTP_X_TIMESTAMP'] ?? '';
        $apiKey    = $_SERVER['HTTP_X_API_KEY'] ?? '';

        if (!$username || !$timestamp || !$apiKey) {
            Response::error(
                'Header API Key tidak lengkap',
                401
            );
        }

        $service = new ApiKeyService(
            new ApiKeyRepository()
        );

        try {
            $service->verify($username, $timestamp, $apiKey);
        } catch (Exception $e) {
            Response::error(
                $e->getMessage(),
                401
            );
        }
    }
}