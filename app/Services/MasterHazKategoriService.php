<?php

namespace App\Services;

use App\Interfaces\MasterHazKategoriInterface;

class MasterHazKategoriService
{
    private MasterHazKategoriInterface $repository;

    public function __construct(MasterHazKategoriInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listKategori(): array
    {
        // future: filter, cache, transform
        return $this->repository->getAll();
    }
}