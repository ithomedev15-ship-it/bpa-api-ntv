<?php

namespace App\Interfaces;

interface TransHazardDetailRepositoryInterface
{
    public function create(array $data): bool;

    public function update(string $kodeHazD1, array $data): bool;

}
