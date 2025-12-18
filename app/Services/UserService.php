<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $repository;

    public function __construct()
    {
        // Manual Dependency Injection
        $this->repository = new UserRepository();
    }

    public function list(): array
    {
        return $this->repository->all();
    }

    public function get(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): int
    {
        // Business rule bisa ditambahkan di sini
        return $this->repository->create($data);
    }
}