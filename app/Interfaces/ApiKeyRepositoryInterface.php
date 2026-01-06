<?php

namespace App\Interfaces;

interface ApiKeyRepositoryInterface
{
    public function isActiveKey(string $username, string $apiKey): bool;
}
