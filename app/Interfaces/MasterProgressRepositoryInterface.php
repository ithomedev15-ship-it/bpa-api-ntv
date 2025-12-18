<?php

namespace App\Interfaces;

interface MasterProgressRepositoryInterface
{
    public function getAll(array $filters = []): array;

    public function findByKode(string $kode): ?array;

    public function create(array $data): void;

}