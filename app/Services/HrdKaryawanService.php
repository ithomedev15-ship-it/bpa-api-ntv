<?php

namespace App\Services;

use App\Interfaces\HrdKaryawanRepositoryInterface;

class HrdKaryawanService
{
    private HrdKaryawanRepositoryInterface $repository;

    public function __construct(HrdKaryawanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listKaryawan(): array
    {
        // future: filter, cache, transform
        return $this->repository->getAll();
    }
}
