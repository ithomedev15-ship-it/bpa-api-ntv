<?php

namespace App\Interfaces;

interface MasterHazKategoriInterface
{
    public function getAll(array $filters = []): array;
}