<?php

namespace App\Services;

use App\Helpers\ApiKeyGenerator;
use App\Interfaces\ApiKeyRepositoryInterface;
use Exception;

class ApiKeyService
{
    private ApiKeyRepositoryInterface $repo;

    public function __construct(ApiKeyRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function verify(string $username, string $timestamp, string $clientKey): void
    {
        $serverKey = ApiKeyGenerator::generate($username, $timestamp);

        if (!hash_equals($serverKey, $clientKey)) {
            throw new Exception('API Key tidak valid');
        }

        if (!$this->repo->isActiveKey($username, $clientKey)) {
            throw new Exception('API Key tidak terdaftar atau nonaktif');
        }
    }
}
