<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function all(): array;
    public function findById(int $id): ?array;
    public function create(array $data): int;

    public function findByUsername(string $username): ?array;
}