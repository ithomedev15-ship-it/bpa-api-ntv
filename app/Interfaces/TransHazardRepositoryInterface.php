<?php

namespace App\Interfaces;

interface TransHazardRepositoryInterface
{
    /**
     * Ambil data hazard (list)
     *
     * @param array $filters
     * @return array
     */
    public function getAll(): array;

    public function create(array $data): bool;

    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
    
    public function update(string $kodeHaz, array $data): bool;
    public function findByKode(string $kodeHaz): ?array;
}
