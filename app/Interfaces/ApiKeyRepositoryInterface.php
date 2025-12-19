<?php

namespace App\Interfaces;

interface ApiKeyRepositoryInterface
{
    public function store(string $apiKey, string $username): void;

    public function findActiveByKey(string $apiKey): ?array;
}
