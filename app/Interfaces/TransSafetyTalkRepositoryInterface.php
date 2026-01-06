<?php

namespace App\Interfaces;

interface TransSafetyTalkRepositoryInterface
{
    public function getAll(array $filter = []): array;
    public function create(array $data): bool;

    public function update(string $kodeStalk, array $data): bool;
    public function delete(string $kodeStalk): bool;
}
